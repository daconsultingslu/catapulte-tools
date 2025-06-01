<?php

namespace App\Entity\Tools\SelfEvaluation;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\SelfEvaluation\SelfEvaluationCriteriaRepository::class)]
class SelfEvaluationCriteria {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    private $name;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\SelfEvaluation\SelfEvaluationTool::class, inversedBy: 'selfEvaluationCriterias')]
    private $selfEvaluationTool;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\SelfEvaluation\SelfEvaluationUserData::class, mappedBy: 'selfEvaluationCriteria', cascade: ['remove'])]
    private $selfEvaluationUserDatas;


    public function __construct() {
        $this->selfEvaluationUserDatas = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSelfEvaluationUserDatas() {
        return $this->selfEvaluationUserDatas;
    }

    /**
     * @param mixed $selfEvaluationUserDatas
     */
    public function setSelfEvaluationUserDatas($selfEvaluationUserDatas): void {
        $this->selfEvaluationUserDatas = $selfEvaluationUserDatas;
    }

    /**
     * @param SelfEvaluationUserData $selfEvaluationUserData
     */
    public function addSelfEvaluationUserData($selfEvaluationUserData): void {
        $this->selfEvaluationUserData->add($selfEvaluationUserData);
    }

    /**
     * @param SelfEvaluationUserData $selfEvaluationUserData
     */
    public function removeSelfEvaluationUserData($selfEvaluationUserData): void {
        $this->selfEvaluationUserData->removeElement($selfEvaluationUserData);
    }

    /**
     * @return mixed
     */
    public function getSelfEvaluationTool() {
        return $this->selfEvaluationTool;
    }

    /**
     * @param mixed $selfEvaluationTool
     */
    public function setSelfEvaluationTool($selfEvaluationTool): void {
        $this->selfEvaluationTool = $selfEvaluationTool;
    }

}
