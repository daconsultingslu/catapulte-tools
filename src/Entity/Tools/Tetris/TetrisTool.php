<?php

namespace App\Entity\Tools\Tetris;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Model\Tool;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Tetris\TetrisToolRepository::class)]
class TetrisTool extends Tool {

    const TYPE = 'tetris_tool';

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Tetris\Level::class, mappedBy: 'tetrisTool')]
    private $levels;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Tetris\TetrisUserData::class, mappedBy: 'tetrisTool')]
    private $tetrisUserDatas;

    public function __construct() {
        $this->levels = new ArrayCollection();
        $this->tetrisUserDatas = new ArrayCollection();
    }

    /**
     * @return Collection|Level[]
     */
    public function getLevels(): Collection
    {
        return $this->levels;
    }

    public function addLevel(Level $level): self
    {
        if (!$this->levels->contains($level)) {
            $this->levels[] = $level;
            $level->setTetrisTool($this);
        }

        return $this;
    }

    public function removeLevel(Level $level): self
    {
        if ($this->levels->contains($level)) {
            $this->levels->removeElement($level);
            // set the owning side to null (unless already changed)
            if ($level->getTetrisTool() === $this) {
                $level->setTetrisTool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TetrisUserData[]
     */
    public function getTetrisUserDatas(): Collection
    {
        return $this->tetrisUserDatas;
    }

    public function addTetrisUserData(TetrisUserData $tetrisUserData): self
    {
        if (!$this->tetrisUserDatas->contains($tetrisUserData)) {
            $this->tetrisUserDatas[] = $tetrisUserData;
            $tetrisUserData->setTetrisTool($this);
        }

        return $this;
    }

    public function removeTetrisUserData(TetrisUserData $tetrisUserData): self
    {
        if ($this->tetrisUserDatas->contains($tetrisUserData)) {
            $this->tetrisUserDatas->removeElement($tetrisUserData);
            // set the owning side to null (unless already changed)
            if ($tetrisUserData->getTetrisTool() === $this) {
                $tetrisUserData->setTetrisTool(null);
            }
        }

        return $this;
    }
}
