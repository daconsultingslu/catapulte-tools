<?php

namespace App\Entity\Tools\WordCloud;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Model\Tool;

#[ORM\Entity(repositoryClass: \App\Repository\Tools\WordCloud\WordCloudToolRepository::class)]
class WordCloudTool extends Tool {

    const TYPE = 'word_cloud_tool';

    #[ORM\Column(type: 'string')]
    private $baseline;

    #[ORM\OneToMany(targetEntity: \App\Entity\Tools\WordCloud\WordCloudUserData::class, mappedBy: 'wordCloudTool', cascade: ['remove'])]
    private $wordCloudUserDatas;

    /**
     * @return mixed
     */
    public function getBaseline() {
        return $this->baseline;
    }

    /**
     * @param mixed $baseline
     */
    public function setBaseline($baseline): void {
        $this->baseline = $baseline;
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

}
