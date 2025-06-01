<?php

namespace App\Entity\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['signature_tool' => 'App\Entity\Tools\Signature\SignatureTool', 'trial_tool' => 'App\Entity\Tools\Trial\TrialTool', 'word_cloud_tool' => 'App\Entity\Tools\WordCloud\WordCloudTool', 'self_evaluation_tool' => 'App\Entity\Tools\SelfEvaluation\SelfEvaluationTool', 'survey_tool' => 'App\Entity\Tools\Survey\SurveyTool', 'qcm_tool' => 'App\Entity\Tools\QCM\QCMTool', 'tetris_tool' => 'App\Entity\Tools\Tetris\TetrisTool'])]
abstract class Tool {

    const TYPE = 'tool';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $name;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Event::class, inversedBy: 'tools')]
    protected $event;

    public static function getType() {
        $c = get_called_class();
        return $c::TYPE;
    }

    public function __toString() {
        return $this->name;
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
     * @return mixed
     */
    public function getEvent() {
        return $this->event;
    }

    /**
     * @param mixed $Event
     */
    public function setEvent($event): void {
        $this->event = $event;
    }
    
}
