<?php

namespace App\Entity\Tools\Trial;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Trial\TrialUserDataRepository::class)]
class TrialUserData {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Trial\TrialTool::class, inversedBy: 'trialUserDatas')]
    private $trialTool;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\User\UserData::class, inversedBy: 'trialUserDatas', cascade: ['remove'])]
    private $userData;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Trial\NumberPlate::class, inversedBy: 'trialUserDatas')]
    private $numberPlate;

    /**
     * @var string $ip
     *
     * @Gedmo\IpTraceable(on="create")
     */
    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private $ip;

    /**
     * @var \DateTime $created
     */
    #[ORM\Column(type: 'datetime')]
    private $created;

    /**
     * @var \DateTime $updated
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
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
     * @return string
     */
    public function getIp(): string {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void {
        $this->ip = $ip;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created): void {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated(\DateTime $updated): void {
        $this->updated = $updated;
    }

}
