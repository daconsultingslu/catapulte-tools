<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Theme;

#[ORM\Entity(repositoryClass: \App\Repository\BrandRepository::class)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    private $name;

    #[ORM\OneToOne(targetEntity: \App\Entity\Theme::class, mappedBy: 'brand')]
    private $theme;

    #[ORM\OneToMany(targetEntity: \App\Entity\Event::class, mappedBy: 'brand')]
    private $events;


    public function __construct() {
        $this->events = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    public function getId()
    {
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
    public function setName(?string $name): void {
      $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getTheme() {
      return $this->theme;
    }

    /**
     * @param mixed $theme
     */
    public function setTheme(?Theme $theme): void {
      $this->theme = $theme;
    }

    /**
     * @return mixed
     */
    public function getEvents() {
        return $this->events;
    }

    /**
     * @param mixed $events
     */
    public function setEvents($events): void {
        $this->events = $events;
    }

    /**
     * @param Event $event
     */
    public function addEvent($event): void {
        $this->events->add($event);
    }

    /**
     * @param Event $event
     */
    public function removeEvent($event): void {
        $this->events->removeElement($event);
    }
}
