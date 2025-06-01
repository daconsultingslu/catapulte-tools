<?php

namespace App\Entity\Tools\SelfEvaluation;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Model\Tool;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\SelfEvaluation\SelfEvaluationToolRepository::class)]
class SelfEvaluationTool extends Tool {

    const TYPE = 'self_evaluation_tool';

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\SelfEvaluation\SelfEvaluationCriteria::class, mappedBy: 'selfEvaluationTool', cascade: ['persist', 'remove'])]
    private $selfEvaluationCriterias;


    public function __construct() {
        $this->selfEvaluationCriterias = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getSelfEvaluationCriterias() {
        return $this->selfEvaluationCriterias;
    }

    /**
     * @param mixed $selfEvaluationCriterias
     */
    public function setSelfEvaluationCriterias($selfEvaluationCriterias): void {
        $this->selfEvaluationCriterias = $selfEvaluationCriterias;
    }

    /**
     * @param SelfEvaluationCriteria $selfEvaluationCriteria
     */
    public function addSelfEvaluationCriteria($selfEvaluationCriteria): void {
        $this->selfEvaluationCriterias->add($selfEvaluationCriteria);
    }

    /**
     * @param SelfEvaluationCriteria $selfEvaluationCriterias
     */
    public function removeSelfEvaluationCriteria($selfEvaluationCriteria): void {
        $this->selfEvaluationCriterias->removeElement($selfEvaluationCriteria);
    }

}
