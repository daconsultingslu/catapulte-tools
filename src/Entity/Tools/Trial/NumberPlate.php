<?php

namespace App\Entity\Tools\Trial;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Trial\NumberPlateRepository::class)]
class NumberPlate {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    private $brand;

    #[ORM\Column(type: 'string')]
    private $model;

    #[ORM\Column(type: 'string')]
    private $numberPlate;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Trial\TrialTool::class, inversedBy: 'numberPlates')]
    private $trialTool;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Trial\TrialUserData::class, mappedBy: 'numberPlate')]
    private $trialUserDatas;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getBrand() {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand): void {
        $this->brand = $brand;
    }

    /**
     * @return mixed
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model): void {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getNumberPlate() {
        return $this->numberPlate;
    }

    /**
     * @param mixed $numberPlate
     */
    public function setNumberPlate($numberPlate): void {
        $this->numberPlate = $numberPlate;
    }

    /**
     * @return mixed
     */
    public function getTrialTool() {
        return $this->trialTool;
    }

    /**
     * @param mixed $trialTool
     */
    public function setTrialTool($trialTool): void {
        $this->trialTool = $trialTool;
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
     * @param TrialUserData $trialUserDatas
     */
    public function removeTrialUserData($trialUserData): void {
        $this->trialUserDatas->removeElement($trialUserData);
    }

}
