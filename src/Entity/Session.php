<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Event;
use App\Entity\Tools\Signature\Qrcode;

#[ORM\Entity(repositoryClass: \App\Repository\SessionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Session
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $startDate;

    #[ORM\Column(type: 'datetime')]
    private $endDate;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Event::class, inversedBy: 'sessions')]
    private $event;

    /**
     * @var string
     */
    #[ORM\Column(name: 'place', type: 'string', length: 255)]
    private $place;

    #[ORM\ManyToMany(targetEntity: \App\Entity\GroupEvent::class, mappedBy: 'sessions', cascade: ['remove'])]
    private $groupEvents;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Signature\SignatureUserData::class, mappedBy: 'session')]
    private $signatureUserDatas;

    #[ORM\OneToMany(targetEntity: Qrcode::class, mappedBy: 'session')]
    private $qrcodes;


    public function __construct() {
        $this->groupEvents = new ArrayCollection();
        $this->signatureUserDatas = new ArrayCollection();
        $this->qrcodes = new ArrayCollection();
    }

    public function __toString() {
        return "Session du ".$this->startDate->format("d/m/Y")." au ".$this->endDate->format("d/m/Y").' / '.$this->event->__toString();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getStartDate() {
      return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void {
      $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate() {
      return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void {
      $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getEvent() {
      return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent(?Event $event): void {
      $this->event = $event;
    }

    /**
     * @return string
     */
    public function getPlace(): ?string {
        return $this->place;
    }

    /**
     * @param string $place
     */
    public function setPlace(string $place): void {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getGroupEvents() {
        return $this->groupEvents;
    }

    /**
     * @param mixed $groupEvents
     */
    public function setGroupEvents($groupEvents): void {
        $this->groupEvents = $groupEvents;
    }

    /**
     * @param GroupEvent $groupEvent
     */
    public function addGroupEvent($groupEvent): void {
        $this->groupEvents->add($groupEvent);
    }

    /**
     * @param GroupEvent $groupEvent
     */
    public function removeGroupEvent($groupEvent): void {
        $this->groupEvents->removeElement($groupEvent);
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

    /**
     * @return Collection<int, Qrcode>
     */
    public function getQrcodes(): Collection
    {
        return $this->qrcodes;
    }

    public function addQrcode(Qrcode $qrcode): self
    {
        if (!$this->qrcodes->contains($qrcode)) {
            $this->qrcodes[] = $qrcode;
            $qrcode->setSession($this);
        }

        return $this;
    }

    public function removeQrcode(Qrcode $qrcode): self
    {
        if ($this->qrcodes->removeElement($qrcode)) {
            // set the owning side to null (unless already changed)
            if ($qrcode->getSession() === $this) {
                $qrcode->setSession(null);
            }
        }

        return $this;
    }

}
