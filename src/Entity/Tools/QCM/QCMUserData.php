<?php

namespace App\Entity\Tools\QCM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User\UserData;
use App\Entity\Tools\QCM\QCMAnswer;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\QCM\QCMUserDataRepository::class)]
class QCMUserData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\User\UserData::class, inversedBy: 'qcmUserDatas')]
    private $userData;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\QCM\QCMQuestion::class, inversedBy: 'qcmUserDatas')]
    private $qcmQuestion;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isRightAnswered;

    #[ORM\Column(type: 'integer')]
    private $countAnswers = 0;

    #[ORM\ManyToMany(targetEntity: \App\Entity\Tools\QCM\QCMAnswer::class)]
    private $qcmAnswers;

    #[ORM\Column(type: 'integer')]
    private $time = 0;


    public function __construct(QCMQuestion $qcmQuestion, UserData $userData) {
        $this->qcmQuestion = $qcmQuestion;
        $this->userData = $userData;
        $this->qcmAnswers = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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
    }

    /**
     * @return mixed
     */
    public function getQCMQuestion() {
        return $this->qcmQuestion;
    }

    /**
     * @param mixed $qcmQuestion
     */
    public function setQCMQuestion($qcmQuestion): void {
        $this->qcmQuestion = $qcmQuestion;
    }

    /**
     * @return bool
     */
    public function isRightAnswered(): ?bool {
        return $this->isRightAnswered;
    }

    /**
     * @param bool $isRightAnswered
     */
    public function setIsRightAnswered(bool $isRightAnswered): void {
        $this->isRightAnswered = $isRightAnswered;
    }

    /**
     * @return mixed
     */
    public function getCountAnswers() {
        return $this->countAnswers;
    }

    /**
     * @param mixed $countAnswers
     */
    public function setCountAnswers($countAnswers): void {
        $this->countAnswers = $countAnswers;
    }


    public function addQCMAnswer(QCMAnswer $qcmAnswer)
    {
      $this->qcmAnswers[] = $qcmAnswer;

      return $this;
    }

    public function removeQCMAnswer(QCMAnswer $qcmAnswer)
    {
      $this->qcmAnswers->removeElement($qcmAnswer);
    }

    public function getQCMAnswers()
    {
      return $this->qcmAnswers;
    }

    /**
     * @return mixed
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void {
        $this->time = intval($time);
    }
}
