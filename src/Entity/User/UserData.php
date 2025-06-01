<?php

namespace App\Entity\User;

use App\Entity\Session;
use App\Entity\Tools\Tetris\TetrisUserData;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User\User;

#[ORM\Entity(repositoryClass: \App\Repository\User\UserDataRepository::class)]
class UserData {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\OneToOne(targetEntity: \App\Entity\User\User::class, inversedBy: 'userData', cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Signature\SignatureUserData::class, mappedBy: 'userData', cascade: ['remove'])]
    private $signatureUserDatas;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Trial\TrialUserData::class, mappedBy: 'userData', cascade: ['remove'])]
    private $trialUserDatas;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\SelfEvaluation\SelfEvaluationUserData::class, mappedBy: 'userData', cascade: ['remove'])]
    private $selfEvaluationUserDatas;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Survey\SurveyUserData::class, mappedBy: 'userData', cascade: ['remove'])]
    private $surveyUserDatas;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\WordCloud\WordCloudUserData::class, mappedBy: 'userData', cascade: ['remove'])]
    private $wordCloudUserDatas;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\QCM\QCMUserData::class, mappedBy: 'userData', cascade: ['remove'])]
    private $qcmUserDatas;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\Tetris\TetrisUserData::class, mappedBy: 'userData')]
    private $tetrisUserDatas;


    public function __construct(User $user) {
        $this->signatureUserDatas = new ArrayCollection();
        $this->trialUserDatas = new ArrayCollection();
        $this->user = $user;
        $this->selfEvaluationUserDatas = new ArrayCollection();
        $this->surveyUserDatas = new ArrayCollection();
        $this->wordCloudUserDatas = new ArrayCollection();
        $this->qcmUserData = new ArrayCollection();
        $this->tetrisUserDatas = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getSignatureUserDatas() {
        return $this->signatureUserDatas;
    }

    /**
     * @return mixed
     */
    public function getFirstSignatureUserDataBySession(Session $session) {
        foreach($this->signatureUserDatas as $ust){
            if($ust->getSession()->getId() === $session->getId()){
                return $ust;
            }
        }

        return false;
    }

    /**
     * @param mixed $signatureUserDatas
     */
    public function setSignatureUserDatas($signatureUserDatas): void {
        $this->signatureUserDatas = $signatureUserDatas;
    }

    /**
     * @param SignatureUserData $signatureUserData
     */
    public function addSignatureUserData($signatureUserData): void {
        $this->signatureUserDatas->add($signatureUserData);
    }

    /**
     * @param SignatureUserData $signatureUserData
     */
    public function removeSignatureUserData($signatureUserData): void {
        $this->signatureUserDatas->removeElement($signatureUserData);
    }

    /**
     * @return mixed
     */
    public function getTrialUserDatas() {
        return $this->trialUserDatas;
    }

    /**
     * @param mixed $trialUserDatas
     */
    public function setTrialUserDatas($trialUserDatas): void {
        $this->trialUserDatas = $trialUserDatas;
    }

    /**
     * @param TrialUserData $trialUserData
     */
    public function addTrialUserData($trialUserData): void {
        $this->trialUserDatas->add($trialUserData);
    }

    /**
     * @param TrialUserData $trialUserData
     */
    public function removeTrialUserData($trialUserData): void {
        $this->trialUserDatas->removeElement($trialUserData);
    }

    /**
     * @return mixed
     */
    public function getSelfEvaluationUserDatas() {
        return $this->selfEvaluationUserDatas;
    }

    /**
     * @param mixed $selfEvaluationUserDatas
     */
    public function setSelfEvaluationUserDatas($selfEvaluationUserDatas): void {
        $this->selfEvaluationUserDatas = $selfEvaluationUserDatas;
    }

    /**
     * @param SelfEvaluationUserData $selfEvaluationUserData
     */
    public function addSelfEvaluationUserData($selfEvaluationUserData): void {
        $this->selfEvaluationUserDatas->add($selfEvaluationUserData);
    }

    /**
     * @param SelfEvaluationUserData $selfEvaluationUserData
     */
    public function removeSelfEvaluationUserData($selfEvaluationUserData): void {
        $this->selfEvaluationUserDatas->removeElement($selfEvaluationUserData);
    }

    /**
     * @return mixed
     */
    public function getSurveyUserDatas() {
        return $this->surveyUserDatas;
    }

    /**
     * @param mixed $surveyUserDatas
     */
    public function setSurveyUserDatas($surveyUserDatas): void {
        $this->surveyUserDatas = $surveyUserDatas;
    }

    /**
     * @param SurveyUserData $surveyUserData
     */
    public function addSurveyUserData($surveyUserData): void {
        $this->surveyUserDatas->add($surveyUserData);
    }

    /**
     * @param SurveyUserData $surveyUserData
     */
    public function removeSurveyUserData($surveyUserData): void {
        $this->surveyUserDatas->removeElement($surveyUserData);
    }

    /**
     * @return mixed
     */
    public function getWordCloudUserDatas() {
        return $this->wordCloudUserDatas;
    }

    /**
     * @param mixed $wordCloudUserDatas
     */
    public function setWordCloudUserDatas($wordCloudUserDatas): void {
        $this->wordCloudUserDatas = $wordCloudUserDatas;
    }

    /**
     * @param WordCloudUserData $wordCloudUserData
     */
    public function addWordCloudUserData($wordCloudUserData): void {
        $this->wordCloudUserDatas->add($wordCloudUserData);
    }

    /**
     * @param WordCloudUserData $wordCloudUserData
     */
    public function removeWordCloudUserData($wordCloudUserData): void {
        $this->wordCloudUserDatas->removeElement($wordCloudUserData);
    }

    /**
     * @return mixed
     */
    public function getQCMUserDatas() {
        return $this->qcmUserDatas;
    }

    /**
     * @param mixed $qcmUserDatas
     */
    public function setQCMUserDatas($qcmUserDatas): void {
        $this->qcmUserDatas = $qcmUserDatas;
    }

    /**
     * @return Collection|TetrisUserData[]
     */
    public function getTetrisUserDatas(): Collection
    {
        return $this->tetrisUserDatas;
    }

    /**
     * @param mixed $tetrisUserDatas
     */
    public function setTetrisUserDatas($tetrisUserDatas): void {
        $this->tetrisUserDatas = $tetrisUserDatas;
    }

    public function addUserTetrisTool(TetrisUserData $tetrisUserData): self
    {
        if (!$this->tetrisUserDatas->contains($tetrisUserData)) {
            $this->tetrisUserDatas[] = $tetrisUserData;
            $tetrisUserData->setUserData($this);
        }

        return $this;
    }

    public function removeTetrisUserData(TetrisUserData $tetrisUserData): self
    {
        if ($this->tetrisUserDatas->contains($tetrisUserData)) {
            $this->tetrisUserDatas->removeElement($tetrisUserData);
            // set the owning side to null (unless already changed)
            if ($tetrisUserData->getUserData() === $this) {
                $tetrisUserData->setUserData(null);
            }
        }

        return $this;
    }

}
