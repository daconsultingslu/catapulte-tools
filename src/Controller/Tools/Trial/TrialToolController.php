<?php

namespace App\Controller\Tools\Trial;

use App\Entity\Event;
use App\Entity\Session;
use App\Entity\Tools\Trial\TrialTool;
use App\Repository\Tools\Trial\NumberPlateRepository;
use App\Repository\Tools\Trial\TrialUserDataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/evenement/{event}/essais', name: 'trial_tool_')]
class TrialToolController extends AbstractController
{
    #[Route('/{tool}', name: 'show')]
    #[Route('/{tool}/session/{session}', name: 'show_session')]
    public function show(
        Event $event,
        TrialTool $tool,
        ?Session $session,
        TrialUserDataRepository $trialUserDataRepository
    ): Response {
        if (!$session) {
            $session = $event->getSessions()->first();
        }

        $trialUserDatas = $trialUserDataRepository->findAllBySession($session, $tool);

        return $this->render('tools/trial/show.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'session' => $session,
            'trialUserDatas' => $trialUserDatas,
        ]);
    }

    #[Route('/{tool}/session/{session}/plaques', name: 'show_number_plates')]
    public function showNumberPlates(Event $event, TrialTool $tool, Session $session, NumberPlateRepository $numberPlates): Response
    {
        $numberPlatesByTool = $numberPlates->findByTrialTool($tool);

        return $this->render('tools/trial/show_number_plates.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'session' => $session,
            'numberPlates' => $numberPlatesByTool,
        ]);
    }

    #[Route('/{tool}/session/{session}/recalculate', name: 'recalculate')]
    public function recalculate(
        Event $event,
        Session $session,
        TrialTool $tool,
        NumberPlateRepository $numberPlateRepo,
        TrialUserDataRepository $trialUserDataRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $numberPlates = $numberPlateRepo->findByTrialTool($tool);

        foreach ($numberPlates as $numberPlate) {
            $trials = $trialUserDataRepository->findAllBySessionAndNumberPlate($session, $numberPlate);

            $nextDateEnd = '';
            foreach ($trials as $trial) {
                if ($nextDateEnd !== '' && $nextDateEnd->format('d') === $trial->getCreated()->format('d')) {
                    $trial->setUpdated($nextDateEnd);
                }
                $nextDateEnd = $trial->getCreated();
            }
        }

        $entityManager->flush();

        $this->addFlash('info', 'Le calcul de fin des essais est terminé.');

        return $this->redirectToRoute('trial_tool_show', [
            'event' => $event->getId(),
            'tool' => $tool->getId(),
        ]);
    }
}