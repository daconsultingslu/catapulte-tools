<?php

namespace App\Controller\Tools\Signature;

use App\Entity\Event;
use App\Entity\ImportExport\ImportExport;
use App\Entity\Session;
use App\Entity\Tools\Signature\SignatureTool;
use App\Entity\Tools\Signature\SignatureUserData;
use App\Entity\User\User;
use App\Form\ImportExport\ImportExportType;
use App\Form\Tools\Signature\SignatureType;
use App\Form\Tools\Signature\UserQrcodeAssignType;
use App\Form\User\UserType;
use App\Repository\QrcodeRepository;
use App\Repository\Tools\Signature\SignatureUserDataRepository;
use App\Repository\User\UserRepository;
use App\Service\DataUrlToImageService;
use App\Service\ImportExportService;
use App\Service\QrcodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\NotBlank;

#[IsGranted('ROLE_ADMIN')]
#[Route('/evenement/{event}/emargement', name: 'signature_tool_')]
class SignatureToolController extends AbstractController
{
    private ImportExportService $importExportService;
    private QrcodeRepository $qrcodeRepository;
    private UserRepository $userRepository;
    private SignatureUserDataRepository $signatureUserDataRepository;
    private DataUrlToImageService $dataUrlToImageService;
    private EntityManagerInterface $entityManager;
    private QrcodeService $qrcodeService;

    public function __construct(
        ImportExportService $importExportService,
        QrcodeRepository $qrcodeRepository,
        UserRepository $userRepository,
        SignatureUserDataRepository $signatureUserDataRepository,
        DataUrlToImageService $dataUrlToImageService,
        EntityManagerInterface $entityManager,
        QrcodeService $qrcodeService
    ) {
        $this->importExportService = $importExportService;
        $this->qrcodeRepository = $qrcodeRepository;
        $this->userRepository = $userRepository;
        $this->signatureUserDataRepository = $signatureUserDataRepository;
        $this->dataUrlToImageService = $dataUrlToImageService;
        $this->entityManager = $entityManager;
        $this->qrcodeService = $qrcodeService;
    }

    #[Route('/{tool}', name: 'show')]
    #[Route('/{tool}/session/{session}', name: 'show_session')]
    public function show(Event $event, SignatureTool $tool, ?Session $session): Response
    {
        if (!$session) {
            $session = $event->getSessions()->first();
        }
        $usersBySession = $this->userRepository->findAllBySession($session);

        return $this->render('tools/signature/show.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'session' => $session,
            'users' => $usersBySession,
        ]);
    }

    #[Route('/{tool}/qrcode', name: 'show_qrcode')]
    #[Route('/{tool}/session/{session}/qrcode', name: 'show_session_qrcode')]
    public function showQrcode(Request $request, Event $event, SignatureTool $tool, ?Session $session): Response
    {
        if (!$session) {
            $session = $event->getSessions()->first();
        }

        $qrcodesBySession = $this->qrcodeRepository->findBy(['session' => $session]);

        $defaultData = ['number' => null];
        $form = $this->createFormBuilder($defaultData)
            ->add('number', IntegerType::class, [
                'constraints' => [new NotBlank()],
                'label' => 'Nombre de qrcodes à créer',
            ])
            ->add('send', SubmitType::class, ['label' => 'Valider'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $nbQrcodes = count($qrcodesBySession);
            for ($i = 0; $i < $data['number']; $i++) {
                $qrcode = $this->qrcodeService->generate($event, $session, $nbQrcodes);
                $this->entityManager->persist($qrcode);
                $nbQrcodes++;
            }
            $this->entityManager->flush();

            $this->addFlash('success', 'Qrcodes générés !!!');

            return $this->redirectToRoute('signature_tool_show_session_qrcode', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'session' => $session->getId(),
            ]);
        }

        return $this->render('tools/signature/show_qrcode.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'session' => $session,
            'qrcodes' => $qrcodesBySession,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{tool}/session/{session}/signature/{user}', name: 'sign')]
    #[Route('/{tool}/session/{session}/signature/{user}/etape/{step}', name: 'sign_step')]
    public function sign(Request $request, Event $event, SignatureTool $tool, Session $session, ?User $user, int $step = 1): Response
    {
        if (!$user->getQrcode()) {
            return $this->redirectToRoute('signature_tool_assign_qrcode', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'session' => $session->getId(),
                'user' => $user->getId(),
            ]);
        }

        $signatureUserData = $this->signatureUserDataRepository->findOneBy([
            'signatureTool' => $tool,
            'session' => $session,
            'userData' => $user->getUserData(),
            'type' => SignatureUserData::TYPE_SIGNATURE,
        ]);

        if (!$signatureUserData) {
            $signatureUserData = new SignatureUserData($tool, $user->getUserData(), SignatureUserData::TYPE_SIGNATURE);
            $signatureUserData->setSession($session);
        }

        $form = $this->createForm(SignatureType::class, $signatureUserData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($signatureUserData);
            $this->entityManager->flush();

            if ($signatureUserData->getIsOff()) {
                return $this->redirectToRoute('signature_tool_show_session', [
                    'event' => $event->getId(),
                    'tool' => $tool->getId(),
                    'session' => $session->getId(),
                ]);
            } elseif ($tool->isWithDischarge() && (!$tool->isMultiSignatures() || $step == 2)) {
                $this->dataUrlToImageService->saveDataToImage($event, $session, $user, $signatureUserData->getType() . $step, $signatureUserData->getSignature());

                return $this->redirectToRoute('signature_tool_discharge', [
                    'event' => $event->getId(),
                    'tool' => $tool->getId(),
                    'session' => $session->getId(),
                    'user' => $user->getId(),
                ]);
            } elseif ($tool->isMultiSignatures() && $step == 1) {
                $this->dataUrlToImageService->saveDataToImage($event, $session, $user, $signatureUserData->getType() . $step, $signatureUserData->getSignature());

                return $this->redirectToRoute('signature_tool_sign_step', [
                    'event' => $event->getId(),
                    'tool' => $tool->getId(),
                    'session' => $session->getId(),
                    'user' => $user->getId(),
                    'step' => 2,
                ]);
            } else {
                $this->dataUrlToImageService->saveDataToImage($event, $session, $user, $signatureUserData->getType() . $step, $signatureUserData->getSignature());

                return $this->redirectToRoute('signature_tool_show_session', [
                    'event' => $event->getId(),
                    'tool' => $tool->getId(),
                    'session' => $session->getId(),
                ]);
            }
        }

        return $this->render('tools/signature/sign.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'user' => $user,
            'step' => $step,
            'signature' => $signatureUserData->getSignature(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{tool}/session/{session}/decharge/{user}', name: 'discharge')]
    public function discharge(Event $event, SignatureTool $tool, Session $session, ?User $user, Request $request): Response
    {
        $signatureUserData = $this->signatureUserDataRepository->findOneBy([
            'signatureTool' => $tool,
            'session' => $session,
            'userData' => $user->getUserData(),
            'type' => SignatureUserData::TYPE_DISCHARGE,
        ]);

        if (!$signatureUserData) {
            $signatureUserData = new SignatureUserData($tool, $user->getUserData(), SignatureUserData::TYPE_DISCHARGE);
            $signatureUserData->setSession($session);
        }

        $form = $this->createForm(SignatureType::class, $signatureUserData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataUrlToImageService->saveDataToImage($event, $session, $user, $signatureUserData->getType(), $signatureUserData->getSignature());
            $this->entityManager->persist($signatureUserData);
            $this->entityManager->flush();

            return $this->redirectToRoute('signature_tool_show_session', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'session' => $session->getId(),
            ]);
        }

        return $this->render('tools/signature/discharge.html.twig', [
            'tool' => $tool,
            'session' => $session,
            'event' => $event,
            'user' => $user,
            'signature' => $signatureUserData->getSignature(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{tool}/ajout-utilisateur/{session}', name: 'user_add')]
    public function addUser(Event $event, SignatureTool $tool, Session $session, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['session' => $session]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setUsername($session->getId() . random_int(1, 9) . bin2hex(random_bytes(2)) . random_int(1, 9));
            $encodedPassword = $userPasswordHasher->hashPassword($user, $user->getUsername());
            $user->setPassword($encodedPassword);
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(true);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Utilisateur ajouté.');

            return $this->redirectToRoute('signature_tool_show_session', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'session' => $session->getId(),
            ]);
        }

        return $this->render('tools/signature/add_user.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{tool}/session/{session}/delete/{user}', name: 'delete_user')]
    public function deleteUser(Event $event, SignatureTool $tool, Session $session, User $user): Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->addFlash('info', 'Utilisateur supprimé !');

        return $this->redirectToRoute('signature_tool_show_session', [
            'event' => $event->getId(),
            'tool' => $tool->getId(),
            'session' => $session->getId(),
        ]);
    }

    #[Route('/{tool}/import', name: 'import')]
    public function import(Request $request, Event $event, SignatureTool $tool, UserRepository $userTempRepository): Response
    {
        $importExport = new ImportExport();
        $form = $this->createForm(ImportExportType::class, $importExport);
        $form->handleRequest($request);

        $userTemps = $userTempRepository->findBy(['isActive' => false]);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->importExportService->setImportExport($importExport);
            $message = $this->importExportService->checkImportUsers($importExport);
            if ($message === true) {
                $this->addFlash('info', 'Fichier valide, prêt pour importer !');
            } else {
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('signature_tool_import', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
            ]);
        }

        return $this->render('import_export/import_users.html.twig', [
            'form' => $form->createView(),
            'tool' => $tool,
            'event' => $event,
            'userTemps' => $userTemps,
        ]);
    }

    public function showState(Event $event, SignatureTool $tool, Session $session, User $user): Response
    {
        $signature = $this->signatureUserDataRepository->findOneBy([
            'userData' => $user->getUserData(),
            'session' => $session,
        ]);

        return $this->render('tools/signature/show_state.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'user' => $user,
            'session' => $session,
            'signature' => $signature,
        ]);
    }

    #[Route('/{tool}/session/{session}/assign-qrcode/{user}', name: 'assign_qrcode')]
    public function assignQrcode(Request $request, Event $event, SignatureTool $tool, Session $session, ?User $user): Response
    {
        $form = $this->createForm(UserQrcodeAssignType::class, $user, ['session' => $session]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', "Le qrcode " . $user->getQrcode() . " a été lié à l'utilisateur " . $user . ".");

            return $this->redirectToRoute('signature_tool_sign', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'session' => $session->getId(),
                'user' => $user->getId(),
            ]);
        }

        return $this->render('tools/signature/assign_qrcode.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}