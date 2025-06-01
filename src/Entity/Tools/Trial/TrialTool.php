<?php

namespace App\Entity\Tools\Trial;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Model\Tool;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Trial\TrialToolRepository::class)]
class TrialTool extends Tool {

    const TYPE = 'trial_tool';

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Trial\NumberPlate::class, mappedBy: 'trialTool', cascade: ['remove'])]
    private $numberPlates;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Trial\TrialUserData::class, mappedBy: 'trialTool', cascade: ['remove'])]
    private $trialUserDatas;


    public function __construct() {
        $this->numberPlates = new ArrayCollection();
        $this->trialUserDatas = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getNumberPlates() {
        return $this->numberPlates;
    }

    /**
     * @param mixed $numberPlates
     */
    public function setNumberPlates($numberPlates): void {
        $this->numberPlates = $numberPlates;
    }

    /**
     * @param NumberPlate $numberPlate
     */
    public function addNumberPlate($numberPlate): void {
        $this->numberPlates->add($numberPlate);
    }

    /**
     * @param NumberPlate $numberPlate
     */
    public function removeNumberPlate($numberPlate): void {
        $this->numberPlates->removeElement($numberPlate);
    }

    /**
     * @return mixed
     */
    public function getTrialUserDatas() {
        return $this->trialUserDatas;
    }

    /**
     * @param mixed $trialUserDatas
     */
    public function setTrialUserDatas($trialUserDatas): void {
        $this->trialUserDatas = $trialUserDatas;
    }

    /**
     * @param TrialUserData $trialUserData
     */
    public function addTrialUserData($trialUserData): void {
        $this->trialUserDatas->add($trialUserData);
    }

    /**
     * @param TrialUserData $trialUserData
     */
    public function removeTrialUserData($trialUserData): void {
        $this->trialUserDatas->removeElement($trialUserData);
    }

}
