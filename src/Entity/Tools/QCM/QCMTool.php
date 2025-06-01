<?php

namespace App\Entity\Tools\QCM;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Model\Tool;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\QCM\QCMToolRepository::class)]
class QCMTool extends Tool {

    const TYPE = 'qcm_tool';

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\QCM\QCMQuestion::class, mappedBy: 'qcmTool', cascade: ['persist', 'remove'])]
    private $qcmQuestions;

    public function __construct() {
        $this->qcmQuestions = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;
        $this->event = null;
        $this->qcmQuestions = $this->qcmQuestions->map(function ($question) {
            return clone $question;
        });
        $this->qcmQuestions->map(function ($question) {
            $question->setQCMTool($this);
            return $question;
        });
    }

    /**
     * @return mixed
     */
    public function getQCMQuestions() {
        return $this->qcmQuestions;
    }

    /**
     * @param mixed $qcmQuestions
     */
    public function setQCMQuestions($qcmQuestions): void {
        $this->qcmQuestions = $qcmQuestions;
    }

    /**
     * @param QCMQuestion $qcmQuestion
     */
    public function addQCMQuestion($qcmQuestion): self {
        if (!$this->qcmQuestions->contains($qcmQuestion)) {
            $this->qcmQuestions[] = $qcmQuestion;
            $qcmQuestion->setQCMTool($this);
        }

        return $this;
    }

    /**
     * @param QCMQuestion $qcmQuestion
     */
    public function removeQCMQuestion($qcmQuestion): self {
        if ($this->qcmQuestions->removeElement($qcmQuestion)) {
            // set the owning side to null (unless already changed)
            if ($qcmQuestion->getQCMTool() === $this) {
                $qcmQuestion->setQCMTool(null);
            }
        }

        return $this;
    }
}
