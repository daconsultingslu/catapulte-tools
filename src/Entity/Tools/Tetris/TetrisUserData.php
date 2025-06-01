<?php

namespace App\Entity\Tools\Tetris;

use App\Entity\User\UserData;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\Tetris\TetrisUserDataRepository::class)]
class TetrisUserData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\Tetris\TetrisTool::class, inversedBy: 'tetrisUserDatas')]
    private $tetrisTool;

    #[ORM\ManyToOne(targetEntity: \App\Entity\User\UserData::class, inversedBy: 'tetrisUserDatas')]
    private $userData;

    #[ORM\Column(type: 'integer')]
    private $score;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserData(): ?UserData
    {
        return $this->userData;
    }

    public function setUserData(?UserData $userData): self
    {
        $this->userData = $userData;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }
}
