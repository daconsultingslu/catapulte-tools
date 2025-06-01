<?php

namespace App\Entity\Tools\Survey;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Model\Question;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Survey\SurveyQuestionRepository::class)]
class SurveyQuestion extends Question
{

    const TYPE = 'survey_question';

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Survey\SurveyAnswer::class, mappedBy: 'surveyQuestion', cascade: ['persist', 'remove'])]
    private $surveyAnswers;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Survey\SurveyTool::class, inversedBy: 'surveyQuestions', cascade: ['persist'])]
    private $surveyTool;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private $usedInExportCalculation = true;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Survey\SurveyUserData::class, mappedBy: 'surveyQuestion')]
    private $surveyUserDatas;


    public function __construct() {
        $this->surveyAnswers = new ArrayCollection();
    }

    public function __clone()
    {
        $this->surveyUserDatas = new ArrayCollection();
        $this->surveyAnswers = $this->surveyAnswers->map(function ($answer) {
            return clone $answer;
        });
        $this->surveyAnswers->map(function ($answer) {
            $answer->setSurveyQuestion($this);
            return $answer;
        });
    }

    /**
     * @return mixed
     */
    public function getSurveyAnswers() {
        return $this->surveyAnswers;
    }

    /**
     * @param mixed $surveyAnswers
     */
    public function setSurveyAnswers($surveyAnswers): void {
        $this->surveyAnswers = $surveyAnswers;
    }

    /**
     * @param SurveyAnswer $surveyAnswer
     */
    public function addSurveyAnswer($surveyAnswer): self {
        if (!$this->surveyAnswers->contains($surveyAnswer)) {
            $this->surveyAnswers[] = $surveyAnswer;
            $surveyAnswer->setSurveyQuestion($this);
        }

        return $this;
    }

    /**
     * @param SurveyAnswer $surveyAnswer
     */
    public function removeSurveyAnswer($surveyAnswer): self {
        if ($this->surveyAnswers->removeElement($surveyAnswer)) {
            // set the owning side to null (unless already changed)
            if ($surveyAnswer->getSurveyQuestion() === $this) {
                $surveyAnswer->setSurveyQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSurveyTool() {
        return $this->surveyTool;
    }

    /**
     * @param mixed $surveyTool
     */
    public function setSurveyTool($surveyTool): void {
        $this->surveyTool = $surveyTool;
    }

    /**
     * @return bool
     */
    public function usedInExportCalculation(): ?bool {
        return $this->usedInExportCalculation;
    }

    /**
     * @param bool $usedInExportCalculation
     */
    public function setUsedInExportCalculation(bool $usedInExportCalculation): void {
        $this->usedInExportCalculation = $usedInExportCalculation;
    }

    /**
     * @return mixed
     */
    public function getSurveyUserDatas() {
        return $this->surveyUserDatas;
    }

    /**
     * @param mixed $surveyUserDatas
     */
    public function setSurveyUserDatas($surveyUserDatas): void {
        $this->surveyUserDatas = $surveyUserDatas;
    }

    /**
     * @param SurveyUserData $surveyUserData
     */
    public function addSurveyUserData($surveyUserData): void {
        $this->surveyUserDatas->add($surveyUserData);
    }

    /**
     * @param SurveyUserData $surveyUserData
     */
    public function removeSurveyUserData($surveyUserData): void {
        $this->surveyUserDatas->removeElement($surveyUserData);
    }

}
