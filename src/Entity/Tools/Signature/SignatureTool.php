<?php

namespace App\Entity\Tools\Signature;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Model\Tool;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Signature\SignatureToolRepository::class)]
class SignatureTool extends Tool {

    const TYPE = 'signature_tool';

    /**
     * @var string
     */
    #[ORM\Column(name: 'trainer', type: 'string', length: 255)]
    private $trainer;

    /**
     * @var string
     */
    #[ORM\Column(name: 'hours', type: 'string', length: 255)]
    private $hours = "De 8h à 18h";

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private $isMultiSignatures;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private $isWithDischarge;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Signature\SignatureUserData::class, mappedBy: 'signatureTool', cascade: ['remove'])]
    private $signatureUserDatas;


    public function __construct() {
        $this->signatureUserDatas = new ArrayCollection();
        $this->isMultiSignatures = true;
        $this->isWithDischarge = true;
    }

    /**
     * @return string
     */
    public function getTrainer(): ?string {
        return $this->trainer;
    }

    /**
     * @param string $trainer
     */
    public function setTrainer(string $trainer): void {
        $this->trainer = $trainer;
    }

    /**
     * @return string
     */
    public function getHours(): ?string {
        return $this->hours;
    }

    /**
     * @param string $hours
     */
    public function setHours(string $hours): void {
        $this->hours = $hours;
    }

    /**
     * @return bool
     */
    public function isMultiSignatures(): ?bool {
        return $this->isMultiSignatures;
    }

    /**
     * @param bool $isMultiSignatures
     */
    public function setIsMultiSignatures(bool $isMultiSignatures): void {
        $this->isMultiSignatures = $isMultiSignatures;
    }

    /**
     * @return bool
     */
    public function isWithDischarge(): ?bool {
        return $this->isWithDischarge;
    }

    /**
     * @param bool $isWithDischarge
     */
    public function setIsWithDischarge(bool $isWithDischarge): void {
        $this->isWithDischarge = $isWithDischarge;
    }

    /**
     * @return mixed
     */
    public function getSignatureUserDatas() {
        return $this->signatureUserDatas;
    }

    /**
     * @param mixed $signatureUserDatas
     */
    public function setSignatureUserDatas($signatureUserDatas): void {
        $this->signatureUserDatas = $signatureUserDatas;
    }

    /**
     * @param SignatureUserData $signatureUserData
     */
    public function addSignatureUserData($signatureUserData): void {
        $this->signatureUserDatas->add($signatureUserData);
    }

    /**
     * @param SignatureUserData $signatureUserData
     */
    public function removeSignatureUserData($signatureUserData): void {
        $this->signatureUserDatas->removeElement($signatureUserData);
    }

}