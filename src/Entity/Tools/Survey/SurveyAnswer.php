<?php

namespace App\Entity\Tools\Survey;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Model\Answer;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Survey\SurveyAnswerRepository::class)]
class SurveyAnswer extends Answer
{
    const TYPE = 'survey_answer';

    /**
     * @var float
     */
    #[ORM\Column(type: 'float')]
    private $value = 0.00;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Survey\SurveyQuestion::class, inversedBy: 'surveyAnswers', cascade: ['persist'])]
    private $surveyQuestion;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Survey\SurveyUserData::class, mappedBy: 'surveyAnswer')]
    private $surveyUserDatas;

    /**
     * @return float
     */
    public function getValue(): ?float {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue(?float $value): void {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getSurveyQuestion() {
        return $this->surveyQuestion;
    }

    /**
     * @param mixed $surveyQuestion
     */
    public function setSurveyQuestion($surveyQuestion): void {
        $this->surveyQuestion = $surveyQuestion;
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
