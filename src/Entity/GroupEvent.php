<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Session;
use App\Entity\User\User;
use App\Entity\User\UserTemp;

#[ORM\Entity(repositoryClass: \App\Repository\GroupEventRepository::class)]
class GroupEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\JoinTable(name: 'group_events_sessions')]
    #[ORM\ManyToMany(targetEntity: \App\Entity\Session::class, inversedBy: 'groupEvents')]
    private $sessions;

    #[ORM\OneToMany(targetEntity: \App\Entity\User\User::class, mappedBy: 'groupEvent', cascade: ['remove'])]
    private $users;


    public function __construct() {
        $this->users = new ArrayCollection();
        $this->sessions = new ArrayCollection();
    }

    public function __toString() {
        return $this->name === null ? '' : $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSessions() {
        return $this->sessions;
    }

    /**
     * @param mixed $session
     */
    public function setSessions(?Session $sessions): void {
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
    public function getUsers() {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users): void {
        $this->users = $users;
    }

    /**
     * @param User $user
     */
    public function addUser($user): void {
        $this->users->add($user);
    }

    /**
     * @param User $user
     */
    public function removeUser($user): void {
        $this->users->removeElement($user);
    }
}
