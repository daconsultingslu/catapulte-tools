<?php

namespace App\Entity\Tools\QCM;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Model\Question;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\QCM\QCMQuestionRepository::class)]
class QCMQuestion extends Question
{
    const TYPE = 'qcm_question';

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\QCM\QCMAnswer::class, mappedBy: 'qcmQuestion', cascade: ['persist', 'remove'])]
    private $qcmAnswers;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\QCM\QCMTool::class, inversedBy: 'qcmQuestions')]
    private $qcmTool;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\QCM\QCMUserData::class, mappedBy: 'qcmQuestion', cascade: ['remove'])]
    private $qcmUserDatas;

    public function __construct() {
        $this->qcmAnswers = new ArrayCollection();
        $this->qcmUserDatas = new ArrayCollection();
    }

    public function __clone()
    {
        $this->qcmUserDatas = new ArrayCollection();
        $this->qcmAnswers = $this->qcmAnswers->map(function ($answer) {
            return clone $answer;
        });
        $this->qcmAnswers->map(function ($answer) {
            $answer->setQCMQuestion($this);
            return $answer;
        });
    }

    /**
     * @return mixed
     */
    public function getQCMAnswers() {
        return $this->qcmAnswers;
    }

    /**
     * @param mixed $qcmAnswers
     */
    public function setQCMAnswers($qcmAnswers): void {
        $this->qcmAnswers = $qcmAnswers;
    }

    /**
     * @param QCMAnswer $qcmAnswer
     */
    public function addQCMAnswer($qcmAnswer): self {
        if (!$this->qcmAnswers->contains($qcmAnswer)) {
            $this->qcmAnswers[] = $qcmAnswer;
            $qcmAnswer->setQCMQuestion($this);
        }

        return $this;
    }

    /**
     * @param QCMAnswer $qcmAnswer
     */
    public function removeQCMAnswer($qcmAnswer): self {
        if ($this->qcmAnswers->removeElement($qcmAnswer)) {
            // set the owning side to null (unless already changed)
            if ($qcmAnswer->getQCMQuestion() === $this) {
                $qcmAnswer->setQCMQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrueQCMAnswers() {
        $answers = array();
        foreach ($this->qcmAnswers as $answer) {
            if($answer->isRightAnswer()) {
                $answers[] = $answer->getId();
            }
        }

        return $answers;
    }

    /**
     * @return mixed
     */
    public function getQCMTool() {
        return $this->qcmTool;
    }

    /**
     * @param mixed $qcmTool
     */
    public function setQCMTool($qcmTool): void {
        $this->qcmTool = $qcmTool;
    }

    /**
     * @return mixed
     */
    public function getQCMUserDatas() {
        return $this->qcmUserDatas;
    }

    /**
     * @param mixed $qcmUserDatas
     */
    public function setQCMUserDatas($qcmUserDatas): void {
        $this->qcmUserDatas = $qcmUserDatas;
    }

    /**
     * @param QCMUserData $qcmUserData
     */
    public function addQCMUserData($qcmUserData): void {
        $this->qcmUserDatas->add($qcmUserData);
    }

    /**
     * @param QCMUserData $qcmUserData
     */
    public function removeQCMUserData($qcmUserData): void {
        $this->qcmUserDatas->removeElement($qcmUserData);
    }

}
