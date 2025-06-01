<?php

namespace App\Controller\Tools\WordCloud;

use App\Entity\Event;
use App\Entity\GroupEvent;
use App\Entity\Tools\WordCloud\WordCloudTool;
use App\Entity\Tools\WordCloud\WordCloudUserData;
use App\Form\Tools\WordCloud\WordCloudType;
use App\Repository\GroupEventRepository;
use App\Repository\Tools\WordCloud\WordCloudUserDataRepository;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/evenement/{event}/nuage-de-mots', name: 'word_cloud_tool_')]
class WordCloudToolController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{tool}', name: 'show')]
    #[Route('/{tool}/groupe/{group}', name: 'show_group')]
    public function show(
        Event $event,
        WordCloudTool $tool,
        ?GroupEvent $group,
        GroupEventRepository $groupEvents,
        WordCloudUserDataRepository $wordCloudToolUserDataRepo
    ): Response {
        $groupsByEvent = $groupEvents->findAllByEvent($event);
        if (!$group) {
            $group = $groupsByEvent[0];
        }
        $words = $wordCloudToolUserDataRepo->findAllByGroup($group);

        return $this->render('tools/word_cloud/show.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'groupEvents' => $groupsByEvent,
            'group' => $group,
            'words' => $words,
        ]);
    }

    #[Route('/{tool}/ajouter-un-mot', name: 'add')]
    public function add(
        Event $event,
        WordCloudTool $tool,
        Request $request,
        UserRepository $userRepo,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $u = $userRepo->find($user->getId());

        $wordCloudToolUserData = new WordCloudUserData($tool, $u->getUserData());
        $form = $this->createForm(WordCloudType::class, $wordCloudToolUserData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($wordCloudToolUserData);
            $entityManager->flush();

            return $this->redirectToRoute('word_cloud_tool_add', [
                'event' => $event->getId(),
                'tool' => $tool->getId(),
            ]);
        }

        return $this->render('tools/word_cloud/add.html.twig', [
            'tool' => $tool,
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }
}