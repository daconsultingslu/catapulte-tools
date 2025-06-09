<?php

namespace App\Entity\Tools\WordCloud;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Model\Tool;
use App\Entity\User\UserData;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\WordCloud\WordCloudUserDataRepository::class)]
class WordCloudUserData {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Tools\WordCloud\WordCloudTool::class, inversedBy: 'wordCloudUserDatas')]
    private $wordCloudTool;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\User\UserData::class, inversedBy: 'wordCloudUserDatas', cascade: ['remove'])]
    private $userData;

    #[ORM\Column(type: 'string')]
    private $word;


    public function __construct(Tool $tool, UserData $userData) {
        $this->wordCloudTool = $tool;
        $this->userData = $userData;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getWordCloudTool() {
        return $this->wordCloudTool;
    }

    /**
     * @param mixed $wordCloudTool
     */
    public function setWordCloudTool($wordCloudTool): void {
        $this->wordCloudTool = $wordCloudTool;
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
    public function getWord() {
        return $this->word;
    }

    /**
     * @param mixed $word
     */
    public function setWord($word): void {
        $this->word = $word;
    }

}
