<?php

namespace App\Controller\Tools\Survey;

use App\Entity\Event;
use App\Entity\Session;
use App\Entity\Tools\Survey\SurveyQuestion;
use App\Entity\Tools\Survey\SurveyTool;
use App\Entity\Tools\Survey\SurveyUserData;
use App\Entity\User\User;
use App\Form\Tools\Survey\SurveyType;
use App\Repository\Tools\Survey\SurveyQuestionRepository;
use App\Repository\Tools\Survey\SurveyUserDataRepository;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/evenement/{event}/enquete-de-satisfaction', name: 'survey_tool_')]
class SurveyToolController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{tool}', name: 'show')]
    #[Route('/{tool}/session/{session}', name: 'show_session')]
    public function show(Event $event, ?Session $session, SurveyTool $tool): Response
    {
        if (!$session) {
            $session = $event->getSessions()->first();
        }

        return $this->render('tools/survey/show.html.twig', [
            'tool' => $tool,
            'session' => $session,
            'event' => $event,
        ]);
    }

    #[Route('/{tool}/question', name: 'show_form')]
    #[Route('/{tool}/question/{question}', name: 'show_form_question')]
    public function showForm(
        Event $event,
        SurveyTool $tool,
        ?SurveyQuestion $question,
        Request $request,
        UserRepository $userRepo,
        SurveyQuestionRepository $surveyQuestionRepository,
        SurveyUserDataRepository $surveyUserDataRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $u = $userRepo->find($user->getId());

        if (!$question) {
            $question = $tool->getSurveyQuestions()->first();
        }

        $surveyUserDataCheck = $surveyUserDataRepository->findByQuestionByUser($question, $user);
        $isSkipped = $surveyUserDataRepository->findQuestionIsSkippedByUser($question, $user);

        if ($surveyUserDataCheck || $isSkipped) {
            $nextQuestion = $this->findNextQuestion($tool, $u, $surveyQuestionRepository, $surveyUserDataRepository);
            if (!$nextQuestion) {
                $this->addFlash('info', 'L\'enquête est terminée, merci d\'y avoir répondu !');

                return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
            }

            return $this->redirectToRoute('survey_tool_show_form_question', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'question' => $nextQuestion->getId(),
            ]);
        }

        $userAnswer = new SurveyUserData($u->getUserData());
        $form = $this->createForm(SurveyType::class, $userAnswer, [
            'entity_manager' => $entityManager,
            'question' => $question,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userAnswer);
            $entityManager->flush();

            $nextQuestion = $this->findNextQuestion($tool, $u, $surveyQuestionRepository, $surveyUserDataRepository);
            if (!$nextQuestion) {
                $this->addFlash('info', 'L\'enquête est terminée, merci d\'y avoir répondu !');

                return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
            }

            return $this->redirectToRoute('survey_tool_show_form_question', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
                'question' => $nextQuestion->getId(),
            ]);
        }

        return $this->render('tools/survey/show_form.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{tool}/skip-question/{question}', name: 'skip_question')]
    public function skipQuestion(
        Event $event,
        SurveyTool $tool,
        SurveyQuestion $question,
        UserRepository $userRepo,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $u = $userRepo->find($user->getId());

        $surveyUserData = new SurveyUserData($u->getUserData());
        $surveyUserData->setSurveyQuestion($question);

        $entityManager->persist($surveyUserData);
        $entityManager->flush();

        return $this->redirectToRoute('survey_tool_show_form', [
            'event' => $event->getId(),
            'tool' => $tool->getId(),
        ]);
    }

    private function findNextQuestion(SurveyTool $tool, User $u, SurveyQuestionRepository $surveyQuestionRepository, SurveyUserDataRepository $surveyUserDataRepository): ?SurveyQuestion
    {
        $questions = $surveyQuestionRepository->findBySurveyTool($tool);
        foreach ($questions as $q) {
            if (!$surveyUserDataRepository->findByQuestionByUser($q, $u) && !$surveyUserDataRepository->findQuestionIsSkippedByUser($q, $u)) {
                return $q;
            }
        }
        return null;
    }
}