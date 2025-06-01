<?php

namespace App\Entity\Tools\SelfEvaluation;

use App\Entity\User\UserData;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\SelfEvaluation\SelfEvaluationUserDataRepository::class)]
class SelfEvaluationUserData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\User\UserData::class, inversedBy: 'selfEvaluationUserDatas', cascade: ['remove'])]
    private $userData;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\SelfEvaluation\SelfEvaluationCriteria::class, inversedBy: 'selfEvaluationUserDatas')]
    private $selfEvaluationCriteria;

    /**
     * @var integer
     */
    #[ORM\Column(name: 'note', type: 'integer')]
    private $note;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $step;


    public function __construct(UserData $userData, SelfEvaluationCriteria $selfEvaluationCriteria, string $step) {
        $this->userData = $userData;
        $this->selfEvaluationCriteria = $selfEvaluationCriteria;
        $this->step = $step;
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
    public function getSelfEvaluationCriteria() {
        return $this->selfEvaluationCriteria;
    }

    /**
     * @param mixed $selfEvaluationCriteria
     */
    public function setSelfEvaluationCriteria($selfEvaluationCriteria): void {
        $this->selfEvaluationCriteria = $selfEvaluationCriteria;
    }

    /**
     * @return int
     */
    public function getNote(): ?int {
        return $this->note;
    }

    /**
     * @param int $note
     */
    public function setNote(int $note): void {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getStep(): string {
        return $this->step;
    }

    /**
     * @param string $step
     */
    public function setStep(string $step): void {
        $this->step = $step;
    }

}
