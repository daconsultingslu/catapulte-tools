<?php

namespace App\Entity\Tools\Survey;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User\UserData;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Survey\SurveyUserDataRepository::class)]
class SurveyUserData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\User\UserData::class, inversedBy: 'surveyUserDatas', cascade: ['remove'])]
    private $userData;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Survey\SurveyQuestion::class, inversedBy: 'surveyUserDatas')]
    private $surveyQuestion;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Survey\SurveyAnswer::class, inversedBy: 'surveyUserDatas')]
    private $surveyAnswer;

    /**
     * @var string
     */
    #[ORM\Column(name: 'comment', type: 'text', nullable: true)]
    private $comment;


    public function __construct(UserData $userData) {
        $this->userData = $userData;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserData() {
        return $this->userData;
    }

    /**
     * @param mixed $userData
     */
    public function setUserData($userData): void {
        $this->userData = $userData;
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
    public function getSurveyAnswer() {
        return $this->surveyAnswer;
    }

    /**
     * @param mixed $surveyAnswer
     */
    public function setSurveyAnswer($surveyAnswer): void {
        $this->surveyAnswer = $surveyAnswer;
    }

    /**
     * @return string
     */
    public function getComment(): ?string {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void {
        $this->comment = $comment;
    }

}
