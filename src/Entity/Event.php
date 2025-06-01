<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $startDate;

    #[ORM\Column(type: 'datetime')]
    private $endDate;

    #[ORM\Column(type: 'string', length: 2)]
    private $language = 'fr';

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Brand::class, inversedBy: 'events')]
    private $brand;

    #[ORM\OneToMany(targetEntity: \App\Entity\Session::class, mappedBy: 'event', cascade: ['remove'])]
    private $sessions;

    #[ORM\OneToMany(targetEntity: \App\Entity\Model\Tool::class, mappedBy: 'event')]
    private $tools;


    public function __construct() {
        $this->sessions = new ArrayCollection();
        $this->tools = new ArrayCollection();
    }

    public function __toString() {
        return $this->name." du ".$this->startDate->format("d/m/Y")." au ".$this->endDate->format("d/m/Y");
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
    public function setName($name): void {
        $this->name = $name;
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
    public function getLanguage() {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language): void {
        $this->language = $language;
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
    public function getSessions() {
        return $this->sessions;
    }

    /**
     * @param mixed $sessions
     */
    public function setSessions($sessions): void {
        $this->sessions = $sessions;
    }

    /**
     * @param Session $session
     */
    public function addSession($session): void {
        $this->sessions->add($session);
    }

    /**
     * @param Session $session
     */
    public function removeSession($session): void {
        $this->sessions->removeElement($session);
    }

    /**
     * @return mixed
     */
    public function getTools() {
        return $this->tools;
    }

    /**
     * @param mixed $tools
     */
    public function setTools($tools): void {
        if(is_null($tools))
        {
            foreach($this->tools as $tool){
                $tool->setEvents(null);
            }
        }
        $this->tools = $tools;
    }

    /**
     * @param Tool $tool
     */
    public function addTool($tool): void {
        $this->tools->add($tool);
    }

    /**
     * @param Tool $tool
     */
    public function removeTool($tool): void {
        $this->tools->removeElement($tool);
    }


}
