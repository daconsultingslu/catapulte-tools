<?php

namespace App\Controller\ImportExport;

use App\Entity\Tools\QCM\QCMTool;
use App\Entity\Tools\Survey\SurveyTool;
use App\Entity\Tools\Signature\SignatureTool;
use App\Entity\Tools\Trial\TrialTool;
use App\Service\ImportExportService;
use App\Entity\Event;
use App\Entity\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ImportExportController extends AbstractController
{
    private ImportExportService $importExportService;

    public function __construct(ImportExportService $importExportService)
    {
        $this->importExportService = $importExportService;
    }

    #[Route('/import/users', name: 'import_users', options: ['expose' => true])]
    public function importUsers(): JsonResponse
    {
        return $this->json($this->importExportService->importUsers());
    }

    #[Route('/export/surveys/{event}/{session}/{tool}', name: 'export_surveys')]
    public function exportSurveys(Event $event, Session $session, SurveyTool $tool): Response
    {
        return $this->importExportService->exportSurvey($event, $session, $tool);
    }

    #[Route('/export/surveys/{event}/{tool}', name: 'export_all_surveys')]
    public function exportAllSurveys(Event $event, SurveyTool $tool): Response
    {
        return $this->importExportService->exportAllSurvey($event, $tool);
    }

    #[Route('/export/signatures/{event}/{tool}/{session}', name: 'export_signatures')]
    public function exportSignatures(Event $event, Session $session, SignatureTool $tool): Response
    {
        return $this->importExportService->exportSignatures($event, $session, $tool);
    }

    #[Route('/export/essais/{event}/{session}/{tool}', name: 'export_trials')]
    public function exportTrials(Event $event, Session $session, TrialTool $tool): Response
    {
        return $this->importExportService->exportTrials($event, $session, $tool);
    }

    #[Route('/export/qcm/{event}/{session}/{tool}', name: 'export_qcm')]
    public function exportQCM(Event $event, Session $session, QCMTool $tool): Response
    {
        return $this->importExportService->exportQCM($event, $session, $tool);
    }

    #[Route('/export/qcm/{event}/{tool}', name: 'export_all_qcm')]
    public function exportAllQCM(Event $event, QCMTool $tool): Response
    {
        return $this->importExportService->exportAllQCM($event, $tool);
    }
}
