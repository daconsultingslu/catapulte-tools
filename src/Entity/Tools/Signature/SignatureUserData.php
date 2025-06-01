<?php

namespace App\Entity\Tools\Signature;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\User\UserData;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Signature\SignatureUserDataRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SignatureUserData {

    const TYPE_SIGNATURE = 1;
    const TYPE_DISCHARGE = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Signature\SignatureTool::class, inversedBy: 'signatureUserDatas')]
    private $signatureTool;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\User\UserData::class, inversedBy: 'signatureUserDatas', cascade: ['remove'])]
    private $userData;

    #[ORM\Column(type: 'integer')]
    private $type;

    #[ORM\Column(type: 'text', nullable: true)]
    private $signature;

    /**
     * @var string $ip
     *
     * @Gedmo\IpTraceable(on="create")
     */
    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private $ip;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isOff;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reason;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     */
    #[ORM\Column(type: 'datetime')]
    private $created;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Session::class, inversedBy: 'signatureUserDatas')]
    private $session;


    public function __construct(SignatureTool $tool, UserData $userData, int $type) {
        $this->signatureTool = $tool;
        $this->userData = $userData;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSignatureTool() {
        return $this->signatureTool;
    }

    /**
     * @param mixed $signatureTool
     */
    public function setSignatureTool($signatureTool): void {
        $this->signatureTool = $signatureTool;
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
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSignature() {
        return $this->signature;
    }

    /**
     * @param mixed $signature
     */
    public function setSignature($signature): void {
        $this->signature = $signature;
    }

    /**
     * @return string
     */
    public function getIp(): ?string {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getIsOff() {
        return $this->isOff;
    }

    /**
     * @param mixed $isOff
     */
    public function setIsOff($isOff): void {
        $this->isOff = $isOff;
    }

    /**
     * @return mixed
     */
    public function getReason() {
        return $this->reason;
    }

    /**
     * @param mixed $reason
     */
    public function setReason($reason): void {
        $this->reason = $reason;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): ?\DateTime {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created): void {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session): void {
        $this->session = $session;
    }

}
