<?php

namespace App\Entity\Tools\Signature;

use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Session;
use App\Repository\QrcodeRepository;

#[ORM\Entity(repositoryClass: QrcodeRepository::class)]
class Qrcode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Session::class, inversedBy: 'qrcodes')]
    private $session;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'qrcode', cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $token;

    #[ORM\Column(type: 'string', length: 255)]
    private $displayedName;

    public function __toString()
    {
        return $this->displayedName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getDisplayedName(): ?string
    {
        return $this->displayedName;
    }

    public function setDisplayedName(string $displayedName): self
    {
        $this->displayedName = $displayedName;

        return $this;
    }
}
