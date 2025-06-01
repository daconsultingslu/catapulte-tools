<?php

namespace App\Entity\Tools\Tetris;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Tetris\WordRepository::class)]
class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    private $name;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $rightAnswer = null;

    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Tetris\Level::class, inversedBy: 'words')]
    private $level;

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
     * @return bool
     */
    public function isRightAnswer(): ?bool {
        return $this->rightAnswer;
    }

    /**
     * @param bool $rightAnswer
     */
    public function setRightAnswer(bool $rightAnswer): void {
        $this->rightAnswer = $rightAnswer;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }
}
