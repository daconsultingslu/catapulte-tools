<?php

namespace App\Controller\Tools\Trial;

use App\Entity\Tools\Signature\Qrcode;
use App\Entity\Tools\Trial\NumberPlate;
use App\Entity\Tools\Trial\TrialUserData;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrialToolRestController extends AbstractController
{
    #[Route('/rest/trial/new', name: 'trial_tool_api', methods: ['POST', 'OPTIONS'])]
    public function post(Request $request, LoggerInterface $logger, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $logger->info('/********** DEBUT ESSAIS **********/ ' . $request->getContent() . ' /********** FIN ESSAIS **********/');

        $objects = $data['essais'];

        $numberPlateRepo = $em->getRepository(NumberPlate::class);
        $qrcodeRepository = $em->getRepository(Qrcode::class);

        foreach ($objects as $object) {
            $qrcode = $qrcodeRepository->findOneBy([
                'session' => $object['user']['token'],
                'token' => $object['user']['nom'],
            ]);

            if (!$qrcode) {
                $logger->error('Qrcode not found for token: ' . $object['user']['token'] . ' and nom: ' . $object['user']['nom']);
                continue;
            }

            $user = $qrcode->getUser();

            $logger->info('token: ' . $object['user']['token'] . ' / object found: ' . $user->getId());

            $numberPlate = $numberPlateRepo->findOneBy([
                'numberPlate' => $object['car']['immatriculation'],
            ]);

            if (!$numberPlate) {
                $logger->error('NumberPlate not found for immatriculation: ' . $object['car']['immatriculation']);
                continue;
            }

            $logger->info('numberPlate: ' . $object['car']['immatriculation'] . ' / object found: ' . $numberPlate->getId());

            $userTrial = new TrialUserData();
            $userTrial->setNumberPlate($numberPlate);
            $userTrial->setUserData($user->getUserData());
            $userTrial->setTrialTool($numberPlate->getTrialTool());
            $userTrial->setCreated(new DateTime($object['user']['created']));
            $userTrial->setUpdated(new DateTime($object['user']['ended']));

            $em->persist($userTrial);
        }

        $em->flush();

        $date = new DateTime();

        $responseData = [
            'id' => uniqid(),
            'time' => $date->format('Y-m-d'),
        ];

        $response = new JsonResponse($responseData);
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}