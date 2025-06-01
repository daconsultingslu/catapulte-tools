<?php

namespace App\Entity\Tools\Tetris;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Tetris\LevelRepository::class)]
class Level
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    private $name;

    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Tetris\TetrisTool::class, inversedBy: 'levels')]
    private $tetrisTool;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Tetris\Word::class, mappedBy: 'level')]
    private $words;


    public function __construct()
    {
        $this->words = new ArrayCollection();
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
    public function setName($name): void {
        $this->name = $name;
    }

    public function getTetrisTool(): ?TetrisTool
    {
        return $this->tetrisTool;
    }

    public function setTetrisTool(?TetrisTool $tetrisTool): self
    {
        $this->tetrisTool = $tetrisTool;

        return $this;
    }

    /**
     * @return Collection|Word[]
     */
    public function getWords(): Collection
    {
        return $this->words;
    }

    public function addWord(Word $word): self
    {
        if (!$this->words->contains($word)) {
            $this->words[] = $word;
            $word->setLevel($this);
        }

        return $this;
    }

    public function removeWord(Word $word): self
    {
        if ($this->words->contains($word)) {
            $this->words->removeElement($word);
            // set the owning side to null (unless already changed)
            if ($word->getLevel() === $this) {
                $word->setLevel(null);
            }
        }

        return $this;
    }
}
