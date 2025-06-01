<?php

namespace App\Controller\Tools\Tetris;

use App\Entity\Event;
use App\Entity\GroupEvent;
use App\Entity\Session;
use App\Entity\Tools\Tetris\TetrisTool;
use App\Entity\Tools\Tetris\TetrisUserData;
use App\Entity\User\User;
use App\Repository\Tools\Tetris\TetrisUserDataRepository;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/evenement/{event}/tetris', name: 'tetris_tool_')]
class TetrisToolController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{tool}', name: 'show')]
    #[Route('/{tool}/session/{session}', name: 'show_session')]
    #[Route('/{tool}/session/{session}/{group}', name: 'show_session_group')]
    public function show(
        Event $event,
        TetrisTool $tool,
        ?Session $session,
        ?GroupEvent $group,
        TetrisUserDataRepository $tetrisUserDataRepository
    ): Response {
        if (!$session) {
            $session = $event->getSessions()->first();
        }

        if (!$group) {
            $group = $session->getGroupEvents()->first();
        }

        $tetrisUserDatas = $tetrisUserDataRepository->findAllByGroup($group);

        return $this->render('tools/tetris/show.html.twig', [
            'tool' => $tool,
            'session' => $session,
            'event' => $event,
            'group' => $group,
            'scores' => $tetrisUserDatas,
        ]);
    }

    #[Route('/{tool}/play', name: 'play')]
    public function play(Event $event, TetrisTool $tool, UserRepository $userRepo): Response
    {
        $formattedData = [];

        foreach ($tool->getLevels() as $level) {
            $data = [
                'levelName' => $level->getName(),
                'done' => false,
                'active' => false,
                'words' => [],
            ];

            foreach ($level->getWords() as $word) {
                $data['words'][] = [
                    'text' => $word->getName(),
                    'fault' => !$word->isRightAnswer(),
                ];
            }

            $formattedData[] = $data;
        }

        $user = $this->getUser();
        $u = $userRepo->find($user->getUsername());

        return $this->render('tools/tetris/play.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'user' => $u,
            'data' => json_encode($formattedData),
        ]);
    }

    #[Route('/{tool}/save-score/{user}/{score}', name: 'save_score', options: ['expose' => true])]
    public function saveScore(
        Event $event,
        TetrisTool $tool,
        User $user,
        int $score,
        Request $request,
        TetrisUserDataRepository $tetrisUserDataRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        if ($request->isXmlHttpRequest()) {
            $tetrisUserData = $tetrisUserDataRepository->findOneBy([
                'user' => $user,
                'tetrisTool' => $tool,
            ]);

            if (!$tetrisUserData) {
                $tetrisUserData = new TetrisUserData();
                $tetrisUserData->setUserData($user->getUserData());
                $tetrisUserData->setTetrisTool($tool);
                $tetrisUserData->setScore(0);
                $entityManager->persist($tetrisUserData);
            } else {
                $tetrisUserData->setScore(0);
            }

            $entityManager->flush();

            return new JsonResponse(['data' => 'Score updated']);
        }

        return new JsonResponse(['error' => 'Invalid request'], Response::HTTP_BAD_REQUEST);
    }
}