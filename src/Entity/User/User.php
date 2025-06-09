<?php

namespace App\Entity\User;

use App\Entity\Tools\Signature\Qrcode;
use App\Entity\User\UserData;
use App\Repository\User\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(targetEntity: \App\Entity\User\UserData::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private $userData;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\GroupEvent::class, inversedBy: 'users')]
    private $groupEvent;

    #[ORM\Column(type: 'string', nullable: true)]
    private $firstname;

    #[ORM\Column(type: 'string', nullable: true)]
    private $lastname;

    #[ORM\Column(type: 'text', nullable: true)]
    private $token;

    #[ORM\Column(type: 'string', nullable: true)]
    private $concessionCode;

    #[ORM\Column(type: 'string', nullable: true)]
    private $details;

    #[ORM\Column(type: 'date', nullable: true)]
    private $expirationDate;

    #[ORM\Column(type: 'boolean')]
    private $isActive = false;

    #[ORM\OneToOne(targetEntity: Qrcode::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private $qrcode;

    public function __construct() {
        $this->userData = new UserData($this);
    }

    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
        $userData->setUser($this);
    }

    /**
     * @return mixed
     */
    public function getGroupEvent() {
        return $this->groupEvent;
    }

    /**
     * @param mixed $groupEvent
     */
    public function setGroupEvent($groupEvent): void {
        $this->groupEvent = $groupEvent;
    }

    /**
     * @return mixed
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getConcessionCode() {
        return $this->concessionCode;
    }

    /**
     * @param mixed $concessionCode
     */
    public function setConcessionCode($concessionCode): void {
        $this->concessionCode = $concessionCode;
    }

    /**
     * @return mixed
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * @param mixed $details
     */
    public function setDetails($details): void {
        $this->details = $details;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?\DateTimeInterface $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getQrcode(): ?Qrcode
    {
        return $this->qrcode;
    }

    public function setQrcode(?Qrcode $qrcode): self
    {
        // unset the owning side of the relation if necessary
        if ($qrcode === null && $this->qrcode !== null) {
            $this->qrcode->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($qrcode !== null && $qrcode->getUser() !== $this) {
            $qrcode->setUser($this);
        }

        $this->qrcode = $qrcode;

        return $this;
    }
}
