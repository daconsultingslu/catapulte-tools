<?php

namespace App\Entity\Tools\QCM;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Model\Answer;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\QCM\QCMAnswerRepository::class)]
class QCMAnswer extends Answer
{
    const TYPE = 'qcm_answer';

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\QCM\QCMQuestion::class, inversedBy: 'qcmAnswers')]
    private $qcmQuestion;

    /**
     * @return mixed
     */
    public function getQCMQuestion() {
        return $this->qcmQuestion;
    }

    /**
     * @param mixed $qcmQuestion
     */
    public function setQCMQuestion($qcmQuestion): void {
        $this->qcmQuestion = $qcmQuestion;
    }

}
