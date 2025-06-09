<?php

namespace App\Controller\Tools\SelfEvaluation;

use App\Entity\Event;
use App\Entity\GroupEvent;
use App\Entity\Tools\SelfEvaluation\SelfEvaluationCriteria;
use App\Entity\Tools\SelfEvaluation\SelfEvaluationTool;
use App\Entity\Tools\SelfEvaluation\SelfEvaluationUserData;
use App\Entity\User\User;
use App\Form\Tools\SelfEvaluation\SelfEvaluationType;
use App\Repository\GroupEventRepository;
use App\Repository\Tools\SelfEvaluation\SelfEvaluationCriteriaRepository;
use App\Repository\Tools\SelfEvaluation\SelfEvaluationUserDataRepository;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/evenement/{event}/auto-evaluation', name: 'self_evaluation_tool_')]
class SelfEvaluationToolController extends AbstractController
{
    public function __construct(
        private readonly SelfEvaluationUserDataRepository $selfEvaluationUserDataRepo,
        private readonly GroupEventRepository $groupEvents,
        private readonly UserRepository $userRepo,
        private readonly SelfEvaluationCriteriaRepository $selfEvaluationCriteriaRepo,
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{tool}', name: 'show')]
    #[Route('/{tool}/groupe/{group}', name: 'show_group')]
    public function show(
        Event $event,
        SelfEvaluationTool $tool,
        ?GroupEvent $group,
    ): Response {
        $groupsByEvent = $this->groupEvents->findAllByEvent($event);
        if (!$group) {
            $group = $groupsByEvent[0];
        }

        $criteriasAveragesByGroup = $this->selfEvaluationUserDataRepo->findAllByStepByGroupByTool('step1', $group, $tool);

        return $this->render('tools/self_evaluation/show.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'group' => $group,
            'groupEvents' => $groupsByEvent,
            'averages' => $criteriasAveragesByGroup,
        ]);
    }

    #[Route('/{tool}/etape/{step}', name: 'show_first_criteria')]
    #[Route('/{tool}/etape/{step}/critere/{criteria}', name: 'show_criteria')]
    public function showCriterias(
        Request $request,
        Event $event,
        SelfEvaluationTool $tool,
        string $step,
        ?SelfEvaluationCriteria $criteria = null,
    ): Response {
        $user = $this->getUser();
        $u = $this->userRepo->find($user->getId());

        if (!$criteria) {
            $criteria = $tool->getSelfEvaluationCriterias()->first();
        }

        $userCriteriasCheck = $this->selfEvaluationUserDataRepo->findByCriteriaByUser($criteria, $user, $step);
        if ($userCriteriasCheck) {
            $nextCriteria = $this->findNextCriteria($u, $tool, $step);
            if (!$nextCriteria) {
                return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
            }

            return $this->redirectToRoute('self_evaluation_tool_show_criteria', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'step' => $step,
                'criteria' => $nextCriteria->getId(),
            ]);
        }

        $selfEvaluationUserData = new SelfEvaluationUserData($u->getUserData(), $criteria, $step);
        $form = $this->createForm(SelfEvaluationType::class, $selfEvaluationUserData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($selfEvaluationUserData);
            $this->entityManager->flush();

            $nextCriteria = $this->findNextCriteria($u, $tool, $step);

            if (!$nextCriteria) {
                $this->addFlash('info', 'L\'auto-évaluation est terminée, merci !');
                return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
            }

            return $this->redirectToRoute('self_evaluation_tool_show_criteria', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'step' => $step,
                'criteria' => $nextCriteria->getId(),
            ]);
        }

        return $this->render('tools/self_evaluation/show_criteria.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'criteria' => $criteria,
            'form' => $form->createView(),
        ]);
    }

    private function findNextCriteria(
        User $u,
        SelfEvaluationTool $tool,
        string $step,
    ): ?SelfEvaluationCriteria {
        $selfEvaluationCriterias = $this->selfEvaluationCriteriaRepo->findBySelfEvaluationTool($tool);
        foreach ($selfEvaluationCriterias as $c) {
            if (!$this->selfEvaluationUserDataRepo->findOneBy(['selfEvaluationCriteria' => $c, 'step' => $step, 'userData' => $u->getUserData()])) {
                return $c;
            }
        }
        return null;
    }

    public function showSelfEvaluationMenu(
        User $user,
        SelfEvaluationTool $tool,
        Event $event,
        UserRepository $userRepository
    ): Response {
        $u = $userRepository->find($user->getId());

        return $this->render('admin/self_evaluation_menu.html.twig', [
            'user' => $u,
            'tool' => $tool,
            'event' => $event,
        ]);
    }
}