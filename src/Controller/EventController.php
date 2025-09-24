<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement', name: 'event_')]
class EventController extends AbstractController
{
    #[Route('/{event}', name: 'show')]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'controller_name' => 'EventController',
            'event' => $event,
        ]);
    }
}