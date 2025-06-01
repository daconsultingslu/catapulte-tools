<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Brand;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: \App\Repository\ThemeRepository::class)]
class Theme
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

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $logo;

    /**
     * @var \DateTime
     */
    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable]
    private $updatedAt;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $writeColor;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $backgroundColor;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\OneToOne(targetEntity: \App\Entity\Brand::class, inversedBy: 'theme')]
    private $brand;


    public function __toString() {
        return $this->name;
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
    public function setName(?string $name): void {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLogo(): ?string {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo(?string $logo): void {
        $this->logo = $logo;
    }

    public function getLogoUrl(): ?string
    {
        if (!$this->logo) {
            return null;
        }
        if (strpos($this->logo, '/') !== false) {
            return $this->logo;
        }
        return sprintf('/uploads/theme/%s', $this->logo);
    }

    /**
     * @return string
     */
    public function getWriteColor(): ?string {
        return $this->writeColor;
    }

    /**
     * @param string $writeColor
     */
    public function setWriteColor(?string $writeColor): void {
        $this->writeColor = $writeColor;
    }

    /**
     * @return string
     */
    public function getBackgroundColor(): ?string {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     */
    public function setBackgroundColor(?string $backgroundColor): void {
        $this->backgroundColor = $backgroundColor;
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
    public function setBrand(?Brand $brand): void {
        $this->brand = $brand;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }


}
