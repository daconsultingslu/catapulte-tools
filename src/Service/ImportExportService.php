<?php

namespace App\Service;

use App\Entity\Tools\Survey\SurveyTool;
use App\Entity\Tools\Signature\SignatureTool;
use App\Entity\Tools\Trial\TrialTool;
use App\Entity\ImportExport\ImportExport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\Event;
use App\Entity\Session;
use App\Entity\User\User;
use App\Entity\Tools\QCM\QCMTool;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Repository\GroupEventRepository;
use App\Repository\Tools\QCM\QCMUserDataRepository;
use App\Repository\Tools\Survey\SurveyAnswerRepository;
use App\Repository\Tools\Survey\SurveyQuestionRepository;
use App\Repository\Tools\Survey\SurveyUserDataRepository;
use App\Repository\Tools\Trial\TrialUserDataRepository;
use App\Repository\User\UserRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ImportExportService {
    private ImportExport $importExport;

    public function __construct(
        private readonly KernelInterface $kernel, 
        private readonly UserPasswordHasherInterface $userPasswordHasher, 
        private readonly EntityManagerInterface $entityManager,
        private readonly GraphBuilderService $graphBuilder,
        private readonly GroupEventRepository $groupEventRepository,
        private readonly UserRepository $userRepository,
        private readonly QCMUserDataRepository $qcmUserDataRepository,
        private readonly TrialUserDataRepository $trialUserDataRepository,
        private readonly SurveyQuestionRepository $surveyQuestionRepository,
        private readonly SurveyAnswerRepository $surveyAnswerRepository,
        private readonly SurveyUserDataRepository $surveyUserDataRepository,
    )
    {}

    public function setImportExport(ImportExport $importExport) {
        $this->importExport = $importExport;
    }

    public function checkImportUsers() {
        $spreadsheet = IOFactory::load($this->importExport->getFile());

        $usersToImport = array();

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            $countRow = 1;
            foreach ($worksheet->getRowIterator() as $row) {
                $data = array();
                $count = 0;
                $emptyCount = 0;
                $flag = true;
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                foreach ($cellIterator as $cell) {
                    if($count == 0){
                        $data['firstname'] = $cell->getCalculatedValue();

                        if($data['firstname'] == '') {
                            $flag = false;
                            $emptyCount++;
                        }
                    }
                    elseif($count == 1){
                        $data['lastname'] = $cell->getCalculatedValue();

                        if($data['lastname'] == '') {
                            $flag = false;
                            $emptyCount++;
                        }
                    }
                    elseif($count == 2){
                        $data['concessionCode'] = $cell->getCalculatedValue();
                        if($data['concessionCode'] == '') {
                            $emptyCount++;
                        }
                    }
                    elseif($count == 3){
                        $data['details'] = $cell->getCalculatedValue();
                        if($data['details'] == '') {
                            $emptyCount++;
                        }
                    }
                    elseif($count == 4){
                        if (!is_null($cell) && $cell->getCalculatedValue() != '') {
                            $data['groupEvent'] = $cell->getCalculatedValue();

                            //On récupère le groupEvent
                            $groupEvent = $this->groupEventRepository->find($cell->getCalculatedValue());
                            if(!$groupEvent) {
                                $flag = false;
                            }
                        }
                        else {
                            $flag = false;
                            $emptyCount++;
                        }
                    }
                    $count++;
                }

                if($flag && $emptyCount < 5) {
                    $usersToImport[] = $data;
                }
                elseif($emptyCount == 5) {

                }
                else {
                    return 'Problème à la ligne '.$countRow.' : le prénom et le nom ne peuvent pas être vides, et le groupe doit exister.';
                }
                $countRow++;
            }
        }

        // Si le parcours du fichier est ok, on importe dans la table temporaire
        foreach($usersToImport as $u) {
            $uTemp = new User();
            $uTemp->setFirstname($u['firstname']);
            $uTemp->setLastname($u['lastname']);
            $uTemp->setConcessionCode($u['concessionCode']);
            $uTemp->setDetails($u['details']);

            //On récupère le groupEvent
            $groupEvent = $this->groupEventRepository->find($u['groupEvent']);
            $uTemp->setGroupEvent($groupEvent);

            $uTemp->setUsername($groupEvent->getId() . random_int(1, 9).bin2hex(random_bytes(2)).random_int(1, 9));
            $uTemp->setPassword('tmp');

            $this->entityManager->persist($uTemp);
        }

        $this->entityManager->flush();

        return true;
    }

    public function importUsers() {
        $userTemps = $this->userRepository->findUsersInactive();

        if(!$userTemps) {
            return false;
        }

        foreach($userTemps as $user) {
            $encodedPassword = $this->userPasswordHasher->hashPassword(
                $user,
                $user->getUsername()
            );
            $user->setPassword($encodedPassword);

            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(true);
        }

        $this->entityManager->flush();

        return true;
    }

    public function exportSignatures(Event $event, Session $session, SignatureTool $tool) {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () use ($event, $session, $tool) {
            $spreadsheet = new Spreadsheet();

            $worksheet = $spreadsheet->getActiveSheet();
            $worksheet->getColumnDimension('E')->setWidth(50);
            $worksheet->getColumnDimension('F')->setWidth(50);
            $worksheet->getColumnDimension('G')->setWidth(50);

            $worksheet
            ->setCellValue('A1', 'Lieu')
            ->setCellValue('B1', $session->getPlace())
            ->setCellValue('A2', 'Horaires')
            ->setCellValue('B2', $tool->getHours())
            ->setCellValue('A3', 'Formateur')
            ->setCellValue('B3', $tool->getTrainer());

            $row = 4;

            $exportPath = $this->kernel->getProjectDir().'/public/uploads/signature/'.$event->getId().'/';

            $exportPath = $this->kernel->getProjectDir().'/public/uploads/signature/'.$event->getId().'/'.$session->getId().'/';

            $row += 2;
            $worksheet
            ->setCellValue('A'.$row, 'Session du '.$session->getStartDate()->format('d/m/Y').' au '.$session->getEndDate()->format('d/m/Y'));

            $row++;
            $worksheet
            ->setCellValue('A'.$row, 'ID')
            ->setCellValue('B'.$row, 'Nom')
            ->setCellValue('C'.$row, 'Prénom')
            ->setCellValue('D'.$row, 'Concession')
            ->setCellValue('E'.$row, 'Détails')
            ->setCellValue('F'.$row, 'Signature 1')
            ->setCellValue('G'.$row, 'Signature 2')
            ->setCellValue('H'.$row, 'Décharge');
            $row++;

            $users = $this->userRepository->findAllBySession($session);

            foreach($users as $user){

                $worksheet->getRowDimension($row)->setRowHeight(75);
                $worksheet
                ->setCellValue('A' . $row, $user->getToken())
                ->setCellValue('B' . $row, $user->getLastname())
                ->setCellValue('C' . $row, $user->getFirstname())
                ->setCellValue('D' . $row, $user->getConcessionCode())
                ->setCellValue('E' . $row, $user->getDetails());

                $uid = $user->getId();
                if(file_exists($exportPath.'signature1/'.$uid.'.jpg') && $this->isImageValid($exportPath.'signature1/'.$uid.'.jpg')) {
                    $drawing00 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    //Check good path
                    $drawing00->setPath($exportPath.'signature1/'.$uid.'.jpg');
                    $drawing00->setHeight(80);
                    $drawing00->setWidth(160);
                    $drawing00->setWorksheet($worksheet);
                    $drawing00->setCoordinates('F' . $row);
                }
                elseif($user->getUserData()->getFirstSignatureUserDataBySession($session) && $user->getUserData()->getFirstSignatureUserDataBySession($session)->getIsOff()) {
                    $worksheet
                    ->setCellValue('E'.$row, 'ABSENT : '. $user->getUserData()->getFirstSignatureUserDataBySession($session)->getReason());
                }

                if(file_exists($exportPath.'signature2/'.$uid.'.jpg') && $this->isImageValid($exportPath.'signature2/'.$uid.'.jpg')) {
                    $drawing01 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    //Check good path
                    $drawing01->setPath($exportPath.'signature2/'.$uid.'.jpg');
                    $drawing01->setHeight(80);
                    $drawing01->setWidth(160);
                    $drawing01->setWorksheet($worksheet);
                    $drawing01->setCoordinates('G' . $row);
                }
                elseif($user->getUserData()->getFirstSignatureUserDataBySession($session) && $user->getUserData()->getFirstSignatureUserDataBySession($session)->getIsOff()) {
                    $worksheet
                    ->setCellValue('E'.$row, 'ABSENT : '. $user->getUserData()->getFirstSignatureUserDataBySession($session)->getReason());
                }

                if(file_exists($exportPath.'decharge/'.$uid.'.jpg') && $this->isImageValid($exportPath.'decharge/'.$uid.'.jpg')) {
                    $drawing02 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    //Check good path
                    $drawing02->setPath($exportPath.'decharge/'.$uid.'.jpg');
                    $drawing02->setHeight(80);
                    $drawing02->setWidth(160);
                    $drawing02->setWorksheet($worksheet);
                    $drawing02->setCoordinates('H' . $row);
                }
                elseif($user->getUserData()->getFirstSignatureUserDataBySession($session) && $user->getUserData()->getFirstSignatureUserDataBySession($session)->getIsOff()) {
                    $worksheet
                    ->setCellValue('E'.$row, 'ABSENT : '. $user->getUserData()->getFirstSignatureUserDataBySession($session)->getReason());
                }

                $row++;
            }

            $writer =  new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="signatures.xlsx"');
        return $streamedResponse->send();
    }

    public function exportTrials(Event $event, Session $session, TrialTool $tool) {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () use ($session, $tool) {
            $userTrialTools = $this->trialUserDataRepository->findAllBySession($session, $tool);

            $spreadsheet = new Spreadsheet();

            $worksheet = $spreadsheet->getActiveSheet();

            $row = 2;
            foreach($userTrialTools as $utt){
                $worksheet->setCellValue('A' . $row, $utt->getUserData()->getUser()->getFirstname());
                $worksheet->setCellValue('B' . $row, $utt->getUserData()->getUser()->getLastname());
                $worksheet->setCellValue('C' . $row, $utt->getNumberPlate()->getNumberPlate());
                $worksheet->setCellValue('D' . $row, $utt->getCreated()->format('Y-m-d H:i:s'));
                $worksheet->setCellValue('E' . $row, $utt->getUpdated()->format('Y-m-d H:i:s'));

                $row++;
            }

            $writer =  new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="essais.xlsx"');
        return $streamedResponse->send();
    }

    public function exportSurvey(Event $event, Session $session, SurveyTool $tool) {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () use ($event, $session, $tool) {
            //On récupère toutes les infos de la base de données
            $letters = $this->createColumnsArray('AZ');

            //Export des enquêtes avec réponses
            $spreadsheet = new Spreadsheet();

            $worksheet = $spreadsheet->getActiveSheet();
            $worksheet->setCellValue('B1', 'Session du ' . $session->getStartDate()->format('d/m/Y') . ' au ' . $session->getEndDate()->format('d/m/Y') . ' à '.$session->getPlace());

            $rowCounter = 2;
            $previousQuestion = 0;
            $previousCalc = true;
            $questionScore = 0;
            $answerCount = 0;
            $questionName = '';
            $totalScore = 0;
            $questionCounter = 0;
            $datas = $this->surveyUserDataRepository->findQuestionsWithCountAnswers($session);
            foreach($datas as $data) {
                if($previousQuestion !== 0 && $previousQuestion != $data['questionId']) {
                    $average = $questionScore / $answerCount;
                    $totalScore = $previousCalc ? $totalScore + $average : $totalScore;
                    $worksheet->setCellValue('A' . $rowCounter, $questionName);
                    $worksheet->setCellValue('B' . $rowCounter, round($average, 2));

                    $questionScore = 0;
                    $answerCount = 0;

                    $rowCounter++;
                    $questionCounter = $previousCalc ? $questionCounter + 1 : $questionCounter;
                }
                $previousQuestion = $data['questionId'];
                $previousCalc = $data['calc'];

                $questionName = $data['questionName'];
                $questionScore += $data['nbAnswers'] * $data['answerValue'];
                $answerCount += $data['nbAnswers'];
            }
            $average = $questionScore / $answerCount;
            $totalScore = $previousCalc ? $totalScore + $average : $totalScore;
            $worksheet->setCellValue('A' . $rowCounter, $questionName);
            $worksheet->setCellValue('B' . $rowCounter, round($average, 2));
            $questionCounter = $previousCalc ? $questionCounter + 1 : $questionCounter;
            $rowCounter += 2;

            $questionCounter = $questionCounter === 0 ? 1 : $questionCounter;

            $worksheet->setCellValue('A' . $rowCounter, 'Moyenne');
            $worksheet->setCellValue('B' . $rowCounter, round($totalScore / $questionCounter, 2));


            // Create another worksheet, and add details for users
            $workSheetDetails = new Worksheet($spreadsheet, 'Details');

            //On récupère les utilisateurs et questions de cet évènement
            $users = $this->userRepository->findAllBySession($session, true);
            $questions = $this->surveyQuestionRepository->findAllByEvent($event, $tool);

            $cptQ = 0;
            for ($i = 1; $i < count($letters); $i += 2) {
                if(isset($questions[$cptQ])) {
                    $workSheetDetails->setCellValue($letters[$i] . '1', $questions[$cptQ]['name']);
                    $workSheetDetails->setCellValue($letters[($i+1)] . '1', "Commentaires");
                    $cptQ++;
                }
                else {
                    break;
                }
            }


            $cptU= 0;
            for ($j=1; $j <= count($users); ++$j) {
                $row = $j +1;
                $cptQ = 0;
                $workSheetDetails->setCellValue($letters[0] . $row, $users[$cptU]['lastname'].' '. $users[$cptU]['firstname']);

                for ($i = 1; $i < count($letters); $i += 2) {
                    if(isset($questions[$cptQ])) {
                        $userAnswers = $this->surveyUserDataRepository->findByQuestionByUserWithIds($questions[$cptQ]['id'], $users[$cptU]['id']);
                        $skipped = $this->surveyUserDataRepository->findQuestionIsSkippedByUserWithIds($questions[$cptQ]['id'], $users[$cptU]['id']);
                        if(!empty($userAnswers)) {
                            $workSheetDetails->setCellValue($letters[$i] . $row, $userAnswers[0]['value']);
                            $workSheetDetails->setCellValue($letters[($i + 1)] . $row, $userAnswers[0]['comment']);
                            $cptQ++;
                        }
                        if(!empty($skipped)) {
                            $workSheetDetails->setCellValue($letters[$i] . $row, '-');
                            $workSheetDetails->setCellValue($letters[($i + 1)] . $row, '-');
                            $cptQ++;
                        }
                    }
                    else {
                        break;
                    }
                }
                $cptU++;
            }

            $spreadsheet->addSheet($workSheetDetails);

            $writer =  new Xlsx($spreadsheet);
            $writer->save('php://output');
        });


        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="enquete_satisfaction.xlsx"');
        return $streamedResponse->send();
    }

    public function exportAllSurvey(Event $event, SurveyTool $tool) {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () use ($event, $tool) {
            $surveyAnswers = $this->surveyAnswerRepository->findDistinctAnswersByTool($tool);

            $datas = [];
            $synthesisDatas = [];
            foreach($event->getSessions() as $session) {
                $datas[$session->getPlace()][$session->getStartDate()->format('d/m')][] = [$session->getPlace() . ' ' . $session->getStartDate()->format('d/m')];
            
                $tmp = [];
                $tmp[] = NULL;
                $tmp[] = 'Note moyenne';
                foreach($surveyAnswers as $answer) {
                    $tmp[] = $answer['name'];
                }
                $tmp[] = 'Nb de votants';
                $datas[$session->getPlace()][$session->getStartDate()->format('d/m')][] = $tmp;

                $synthesisDatas[$session->getPlace()][$session->getStartDate()->format('d/m')][] = [null, null];
                $synthesisDatas[$session->getPlace()][$session->getStartDate()->format('d/m')][] = ['Note moyenne', 'Nb de votants'];

                $totalForAverage = 0;
                $nbQuestionsForAverageCalculation = 0;
                foreach($tool->getSurveyQuestions() as $question) {
                    // Init line
                    $tmp = [];
                    $tmp[] = $question->getName();
                    $tmp[] = 0;
                    foreach($surveyAnswers as $answer) {
                        $tmp[] = 0;
                    }
                    $tmp[] = 0;

                    // Then, put real values
                    $totalVoting = 0;
                    $totalScore = 0;
                    $questionDatas = $this->surveyUserDataRepository->findQuestionsWithCountAnswers($tool, $session, $question);
                    $offset = 2;
                    foreach($surveyAnswers as $answer) {
                        $voting = 0;
                        foreach($questionDatas as $data) {
                            if($answer['value'] === $data['value']) {
                                $tmp[$offset] = $data['nbAnswers'];
                                $voting = $data['nbAnswers'];
                                $totalScore += ($voting * $data['value']);
                                $totalVoting += $voting;
                            }
                        }
                        
                        $offset++;
                    }
                    $average = $totalVoting === 0 ? 0 : round($totalScore / $totalVoting, 2);
                    $tmp[1] = $average;
                    $tmp[$offset] = $totalVoting;
                    $datas[$session->getPlace()][$session->getStartDate()->format('d/m')][] = $tmp;

                    // Synthèse lieu date par date
                    $synthesisDatas[$session->getPlace()][$session->getStartDate()->format('d/m')][] = [$average, $totalVoting];

                    if($question->usedInExportCalculation()) {
                        $nbQuestionsForAverageCalculation++;
                        $totalForAverage += $average;
                    }
                }

                if ($nbQuestionsForAverageCalculation === 0) {
                    $nbQuestionsForAverageCalculation = 1;
                }
                $totalAverage = round(($totalForAverage / $nbQuestionsForAverageCalculation), 2);
                $datas[$session->getPlace()][$session->getStartDate()->format('d/m')][] = [
                    'MOYENNE TOTAL',
                    $totalAverage
                ];

                $synthesisDatas[$session->getPlace()][$session->getStartDate()->format('d/m')][] = [$totalAverage, null];
            }

            // Loop to resume all dates for 1 place
            $globalHeader[] = null;
            $globalHeader[] = 'Moyenne globale';
            foreach ($synthesisDatas as $place => $dates) {
                if (!is_int($place)) {
                    $globalHeader[] = $place;
                    // Synthèse lieu somme
                    $synthesisDatas[$place]['sum'][] = [$place, null, null];
                    $synthesisDatas[$place]['sum'][] = [null, 'Nombre de votants', 'Note moyenne'];


                    foreach ($tool->getSurveyQuestions() as $question) {
                        $synthesisDatas[$place]['sum'][] = [$question->getName(), 0, 0];
                    }
                    
                    foreach ($dates as $date => $notes) {
                        $offset = 2;
                        array_pop($notes);
                        array_shift($notes);
                        array_shift($notes);
                        foreach ($notes as $values) {
                            $synthesisDatas[$place]['sum'][$offset][1] += $values[1];
                            $synthesisDatas[$place]['sum'][$offset][2] += ($values[0] * $values[1]);
                            $offset++;
                        }
                    }

                    $sumAveragePlace = 0;
                    $totalQuestions = count($synthesisDatas[$place]['sum']) - 2;
                    for ($i = 2; $i < count($synthesisDatas[$place]['sum']); $i++) {
                        $sum = 1;
                        if ($synthesisDatas[$place]['sum'][$i][1] !== 0) {
                            $sum = $synthesisDatas[$place]['sum'][$i][1];
                        }
                        $average = $synthesisDatas[$place]['sum'][$i][2] / $sum;
                        $synthesisDatas[$place]['sum'][$i][2] = round($average, 2);

                        $sumAveragePlace += $average;
                    }
                    $synthesisDatas[$place]['sum'][] = [null, null, round(($sumAveragePlace / $totalQuestions), 2)];
                }
            }

            $global[] = $globalHeader; 

            $offset = 2;
            $nbLines = 0;
            foreach($synthesisDatas as $placeDatas) {
                $averages = [];
                for ($i = 2; $i < count($placeDatas['sum']); $i++) {
                    $global[($i - 1)][0] = $placeDatas['sum'][$i][0];
                    $global[($i - 1)][1] = 0;
                    $global[($i - 1)][$offset] = $placeDatas['sum'][$i][2];
                    $averages[($i -2)] = 
                        isset($averages[($i -2)]) 
                        ? $averages[($i -2)] + $placeDatas['sum'][$i][2]
                        : $placeDatas['sum'][$i][2]
                    ;
                }
                $offset++;

                $nbLines = count($placeDatas['sum']);
            }

            $loop = true;
            $i = 2;
            while ($loop) {
                $global[($i - 1)][1] = $averages[($i - 2)] / count($synthesisDatas);
                $i++;
                if ( !isset($global[($i - 1)]) ) {
                    $loop = false;
                }
            }

            $tmp = [];
            foreach($synthesisDatas as $place => $placeDatas) {
                for($i = 0; $i < $nbLines; $i++) {
                    $tmp[$i] = [];
                    $tmp[$i] = array_merge($tmp[$i], $placeDatas['sum'][$i]);
                    $tmp[$i][] = null;

                    foreach($placeDatas as $date => $subdatas) {
                        if ($date != 'sum') {
                            $tmp[$i] = array_merge($tmp[$i], $subdatas[$i]);
                        }
                    }
                }
                $synthesisDatas[$place][] = $tmp;
            }

            $datas['Synthèse'] = $synthesisDatas;
            $datas['Synthèse']['Global'][0] = $global;

            // On parcourt les données et on écrit dans le fichier
            $spreadsheet = new Spreadsheet();

            foreach($datas as $place => $datasPlace) {
                if ($place != 'Synthèse') {
                    $worksheet = new Worksheet($spreadsheet, $place);
                    
                    $line = 1;
                    foreach($datasPlace as $date => $datasSession) {

                        $worksheet->fromArray($datasSession, NULL, 'A' . $line);

                        $chart = $this->graphBuilder->build($datasSession, $line);
                        // Add the chart to the worksheet
                        $worksheet->addChart($chart);

                        $line += (count($datasSession) + 5);
                    }

                    $spreadsheet->addSheet($worksheet);
                }
                else {
                    $worksheet = new Worksheet($spreadsheet, $place);

                    $line = 1;
                    foreach($datasPlace as $place => $subdatasPlace) {
                        foreach ($subdatasPlace as $key => $values) {
                            if (is_int($key)) {
                                $worksheet->fromArray($values, NULL, 'A' . $line);

                                $line += (count($values) + 5);
                            }
                        }
                    }

                    $spreadsheet->addSheet($worksheet);
                }
            }

            $spreadsheet->removeSheetByIndex(0);

            $writer =  new Xlsx($spreadsheet);
            $writer->setIncludeCharts(true);
            $writer->save('php://output');
        });

        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="enquete_satisfaction.xlsx"');
        return $streamedResponse->send();
    }

    public function exportQCM(Event $event, Session $session, QCMTool $tool) {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () use ($session, $tool) {
            $spreadsheet = new Spreadsheet();
            $worksheet = $spreadsheet->getActiveSheet();

            // One question per row
            $questions = $tool->getQCMQuestions();
            $nbQuestions = count($questions);
            $rowCounter = 2;
            foreach($questions as $question) {
                $worksheet->setCellValue('A'.$rowCounter, $question->getName());

                $rowCounter++;
            }
            $worksheet->setCellValue('A'.($rowCounter + 2), 'Moyenne');

            $rowCounter = 2;
            $total = 0;
            $worksheet->setCellValue('B1', 'Session du ' . $session->getStartDate()->format('d/m/Y') . ' au ' . $session->getEndDate()->format('d/m/Y') . ' à '.$session->getPlace());
            $answers = $this->qcmUserDataRepository->getAverageTrueAnswerByQuestionBySession($session, $tool);
            foreach($answers['all'] as $answerAll) {
                $nbTotalAnswers = $answerAll['nbAll'];

                $true = 0;
                foreach($answers['true'] as $answerTrue) {
                    if($answerTrue['question'] === $answerAll['question']) {
                        $true = $answerTrue['nbTrue'];
                        break;
                    }
                }

                $percent = 0;
                if(true !== 0) {
                    $percent = round(($true / $nbTotalAnswers) * 100, 2);
                }
                $total += $percent;

                $worksheet->setCellValue('B' . $rowCounter, $percent.'%');

                $rowCounter++;
            }

            $worksheet->setCellValue('B'.($rowCounter + 2), round(($total / $nbQuestions), 2).'%');

            // Create another worksheet, and add details for users
            $workSheetDetails = new Worksheet($spreadsheet, 'Details');

            $users = $this->userRepository->findAllBySession($session, true);

            $letters = $this->createColumnsArray('AZ');
            $i = 1;
            foreach ($questions as $question) {
                $workSheetDetails->setCellValue($letters[$i] . '1', $question->getName());
                $i++;
            }

            $cptU= 0;
            for ($row = 2; $row <= (count($users) + 1); ++$row) {
                $workSheetDetails->setCellValue($letters[0] . $row, $users[$cptU]['lastname'].' '. $users[$cptU]['firstname']);

                $cptQ = 1;
                foreach ($questions as $question) {
                    $formatted = '';
                    // Get answers for users
                    $qcmUserDatas = $this->qcmUserDataRepository->findByQCMQuestionByUserId($question, $users[$cptU]['id']);

                    // Format
                    foreach ($qcmUserDatas as $qud) {
                        foreach ($qud->getQCMAnswers() as $answer) {
                            $formatted .= $answer->getName().', ';
                        }
                    }
                    $formatted = substr($formatted, 0, -2);
                    // Print
                    $workSheetDetails->setCellValue($letters[$cptQ] . $row, $formatted);
                    $cptQ++;
                }
                $cptU++;
            }

            $spreadsheet->addSheet($workSheetDetails);

            // Create another worksheet, and add details for users
            $workSheetOrder = new Worksheet($spreadsheet, 'Classement');

            $datas = $this->qcmUserDataRepository->getScoreByUserBySession($session, $tool);

            $workSheetOrder->setCellValue('A1', 'Participant');
            $workSheetOrder->setCellValue('B1', 'Nombre de bonnes réponses');
            $workSheetOrder->setCellValue('C1', 'Temps passé');

            $cpt= 0;
            for ($row = 2; $row <= (count($datas) + 1); ++$row) {
                $workSheetOrder->setCellValue('A' . $row, $datas[$cpt]['lastname'] .' ' . $datas[$cpt]['firstname']);
                $workSheetOrder->setCellValue('B' . $row, $datas[$cpt]['nbRight'] . ' / ' . count($tool->getQcmQuestions()));
                $workSheetOrder->setCellValue('C' . $row, $datas[$cpt]['timeAnswer'] / 100 . ' secondes');
                $cpt++;
            }

            $spreadsheet->addSheet($workSheetOrder);

            $writer =  new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="qcm.xlsx"');
        return $streamedResponse->send();
    }

    public function exportAllQCM(Event $event, QCMTool $tool) {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () use ($event, $tool) {
            $spreadsheet = new Spreadsheet();
            $worksheet = $spreadsheet->getActiveSheet();

            // One question per row
            $questions = $tool->getQCMQuestions();
            $nbQuestions = count($questions);
            $rowCounter = 2;
            foreach($questions as $question) {
                $worksheet->setCellValue('A'.$rowCounter, $question->getName());

                $rowCounter++;
            }
            $worksheet->setCellValue('A'.($rowCounter + 2), 'Moyenne');

            $letters = $this->createColumnsArray('AZ');
            $counterColumn = 1;
            foreach($event->getSessions() as $session) {
                $rowCounter = 2;
                $total = 0;
                $worksheet->setCellValue($letters[$counterColumn].'1', 'Session du ' . $session->getStartDate()->format('d/m/Y') . ' au ' . $session->getEndDate()->format('d/m/Y') . ' à '.$session->getPlace());
                $answers = $this->qcmUserDataRepository->getAverageTrueAnswerByQuestionBySession($session);
                foreach($answers['all'] as $answerAll) {
                    $nbTotalAnswers = $answerAll['nbAll'];

                    $true = 0;
                    foreach($answers['true'] as $answerTrue) {
                        if($answerTrue['question'] === $answerAll['question']) {
                            $true = $answerTrue['nbTrue'];
                            break;
                        }
                    }

                    $percent = 0;
                    if(true !== 0) {
                        $percent = round(($true / $nbTotalAnswers) * 100, 2);
                    }
                    $total += $percent;

                    $worksheet->setCellValue($letters[$counterColumn] . $rowCounter, $percent.'%');

                    $rowCounter++;
                }

                $worksheet->setCellValue($letters[$counterColumn].($rowCounter + 2), round(($total / $nbQuestions), 2).'%');

                $counterColumn++;


                // Create another worksheet, and add details for users
                $invalidCharacters = $worksheet->getInvalidCharacters();
                $title = str_replace($invalidCharacters, ' ', 'Détails ' . $session->getStartDate()->format('d/m/Y') . '/' . $session->getEndDate()->format('d/m/Y'));
                $workSheetDetails = new Worksheet($spreadsheet, $title);

                $users = $this->userRepository->findAllBySession($session, true);

                $i = 1;
                foreach ($questions as $question) {
                    $workSheetDetails->setCellValue($letters[$i] . '1', $question->getName());
                    $i++;
                }

                $cptU= 0;
                for ($row = 2; $row <= (count($users) + 1); ++$row) {
                    $workSheetDetails->setCellValue($letters[0] . $row, $users[$cptU]['lastname'].' '. $users[$cptU]['firstname']);

                    $cptQ = 1;
                    foreach ($questions as $question) {
                        $formatted = '';
                        // Get answers for users
                        $qcmUserDatas = $this->qcmUserDataRepository->findByQCMQuestionByUserId($question, $users[$cptU]['id']);

                        // Format
                        foreach ($qcmUserDatas as $qud) {
                            foreach ($qud->getQCMAnswers() as $answer) {
                                $formatted .= $answer->getName().', ';
                            }
                        }
                        $formatted = substr($formatted, 0, -2);
                        // Print
                        $workSheetDetails->setCellValue($letters[$cptQ] . $row, $formatted);
                        $cptQ++;
                    }
                    $cptU++;
                }

                $spreadsheet->addSheet($workSheetDetails);
            }

            $writer =  new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="qcm.xlsx"');
        return $streamedResponse->send();
    }

    function createColumnsArray($end_column, $first_letters = '')
    {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
                return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = $this->createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }

    function isImageValid(string $filePath): bool
    {
        $imageInfo = @getimagesize($filePath);

        if ($imageInfo === false) {
            return false;
        }

        $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];

        if (in_array($imageInfo[2], $allowedTypes)) {
            return true;
        }

        return false;
    }
}
