<?php

namespace App\Entity\Tools\Survey;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Model\Tool;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Survey\SurveyToolRepository::class)]
class SurveyTool extends Tool {

    const TYPE = 'survey_tool';

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Survey\SurveyQuestion::class, mappedBy: 'surveyTool', cascade: ['persist', 'remove'])]
    private $surveyQuestions;

    public function __construct() {
        $this->surveyQuestions = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;
        $this->event = null;
        $this->surveyQuestions = $this->surveyQuestions->map(function ($question) {
            return clone $question;
        });
        $this->surveyQuestions->map(function ($question) {
            $question->setSurveyTool($this);
            return $question;
        });
    }

    /**
     * @return mixed
     */
    public function getSurveyQuestions() {
        return $this->surveyQuestions;
    }

    /**
     * @param mixed $surveyQuestions
     */
    public function setSurveyQuestions($surveyQuestions): void {
        $this->surveyQuestions = $surveyQuestions;
    }

    /**
     * @param SurveyQuestion $surveyQuestion
     */
    public function addSurveyQuestion($surveyQuestion): self {
        if (!$this->surveyQuestions->contains($surveyQuestion)) {
            $this->surveyQuestions[] = $surveyQuestion;
            $surveyQuestion->setSurveyTool($this);
        }

        return $this;
    }

    /**
     * @param SurveyQuestion $surveyQuestion
     */
    public function removeSurveyQuestion($surveyQuestion): self {
        if ($this->surveyQuestions->removeElement($surveyQuestion)) {
            // set the owning side to null (unless already changed)
            if ($surveyQuestion->getSurveyTool() === $this) {
                $surveyQuestion->setSurveyTool(null);
            }
        }

        return $this;
    }

}
