<?php

namespace App\Controller\Tools\QCM;

use App\Entity\Event;
use App\Entity\Session;
use App\Entity\Tools\QCM\QCMQuestion;
use App\Entity\Tools\QCM\QCMTool;
use App\Entity\Tools\QCM\QCMUserData;
use App\Entity\User\User;
use App\Form\Tools\QCM\QCMType;
use App\Repository\Tools\QCM\QCMAnswerRepository;
use App\Repository\Tools\QCM\QCMQuestionRepository;
use App\Repository\Tools\QCM\QCMUserDataRepository;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/evenement/{event}/qcm', name: 'qcm_tool_')]
class QCMToolController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly QCMUserDataRepository $qcmUserDataRepository,
        private readonly UserRepository $userRepo,
        private readonly QCMQuestionRepository $qcmQuestionRepository,
        private readonly QCMAnswerRepository $qcmAnswerRepository,
    )
    {
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{tool}', name: 'show')]
    #[Route('/{tool}/session/{session}', name: 'show_session')]
    public function show(
        Event $event,
        QCMTool $tool,
        ?Session $session,
    ): Response {
        if (!$session) {
            $session = $event->getSessions()->first();
        }

        $data = $this->qcmUserDataRepository->getScoreByUserBySession($session, $tool);

        return $this->render('tools/qcm/show.html.twig', [
            'tool' => $tool,
            'session' => $session,
            'event' => $event,
            'datas' => $data,
        ]);
    }

    #[Route('/{tool}/question', name: 'show_form')]
    #[Route('/{tool}/question/{qcmQuestion}', name: 'show_form_question')]
    public function showForm(
        Request $request,
        Event $event,
        QCMTool $tool,
        ?QCMQuestion $qcmQuestion,
    ): Response {
        $user = $this->getUser();
        $u = $this->userRepo->find($user->getId());

        if (!$qcmQuestion) {
            $qcmQuestion = $tool->getQCMQuestions()->first();
        }

        $qcmUserDataCheck = $this->qcmUserDataRepository->findByQCMQuestionByUser($qcmQuestion, $user);
        if ($qcmUserDataCheck) {
            $nextQcmQuestion = $this->findNextQcmQuestion($tool, $u);
            if (!$nextQcmQuestion) {
                $scores = $this->qcmUserDataRepository->getAverageTrueAnswerByQuestionBySession(null, $tool, $u);
                $finalScore = round(count($scores['true']) / count($scores['all']) * 100);

                $this->addFlash('info', 'Le QCM est terminé, merci d\'y avoir répondu ! Votre score final est de ' . $finalScore . '%');

                return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
            }

            return $this->redirectToRoute('qcm_tool_show_form_question', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'qcmQuestion' => $nextQcmQuestion->getId(),
            ]);
        }

        $qcmUserData = new QCMUserData($qcmQuestion, $u->getUserData());

        $form = $this->createForm(QCMType::class, $qcmUserData, [
            'qcmQuestion' => $qcmQuestion,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answers = $form->getData()->getQCMAnswers()->toArray() ?? [];
            if (!is_array($answers)) {
                $answers = [$answers];
            }

            if (empty($answers)) {
                $this->addFlash('warning', 'Au moins une réponse est obligatoire.');

                return $this->redirectToRoute('qcm_tool_show_form', [
                    'event' => $event->getId(),
                    'tool' => $tool->getId(),
                ]);
            }

            $qcmUserData->setQCMQuestion($qcmQuestion);
            $qcmUserData->setIsRightAnswered($this->checkIfRightAnswered($qcmQuestion, $answers));
            $qcmUserData->setCountAnswers(count($answers));

            foreach ($answers as $answer) {
                $qcmAnswer = $this->qcmAnswerRepository->find($answer->getId());
                $qcmUserData->addQCMAnswer($qcmAnswer);
            }

            $this->entityManager->persist($qcmUserData);
            $this->entityManager->flush();

            $nextQcmQuestion = $this->findNextQcmQuestion($tool, $u);
            if (!$nextQcmQuestion) {
                $scores = $this->qcmUserDataRepository->getAverageTrueAnswerByQuestionBySession(null, $tool, $u);
                $finalScore = round(count($scores['true']) / count($scores['all']) * 100);

                $this->addFlash('info', 'Le QCM est terminé, merci d\'y avoir répondu ! Votre score final est de ' . $finalScore . '%');

                return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
            }

            return $this->redirectToRoute('qcm_tool_show_form_question', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'qcmQuestion' => $nextQcmQuestion->getId(),
            ]);
        }

        return $this->render('tools/qcm/show_form.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'qcmQuestion' => $qcmQuestion,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{tool}/skip-question/{qcmQuestion}', name: 'skip_question')]
    public function skipQuestion(
        Event $event,
        QCMTool $tool,
        QCMQuestion $qcmQuestion,
    ): Response {
        $user = $this->getUser();
        $u = $this->userRepo->find($user->getId());

        $userQcmAnswer = new QCMUserData($qcmQuestion, $u->getUserData());

        $this->entityManager->persist($userQcmAnswer);
        $this->entityManager->flush();

        return $this->redirectToRoute('qcm_tool_show_form', [
            'event' => $event->getId(),
            'tool' => $tool->getId(),
        ]);
    }

    private function findNextQcmQuestion(QCMTool $tool, User $u): ?QCMQuestion
    {
        $qcmQuestions = $this->qcmQuestionRepository->findByQcmTool($tool);
        foreach ($qcmQuestions as $q) {
            if (!$this->qcmUserDataRepository->findByQcmQuestionByUser($q, $u)) {
                return $q;
            }
        }
        return null;
    }

    private function checkIfRightAnswered(QCMQuestion $question, array $answers): bool
    {
        $trueAnswers = $question->getTrueQCMAnswers();

        if (count($trueAnswers) !== count($answers)) {
            return false;
        }

        sort($trueAnswers);
        sort($answers);

        return $trueAnswers === $answers;
    }
}