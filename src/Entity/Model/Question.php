<?php

namespace App\Entity\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['survey_question' => 'App\Entity\Tools\Survey\SurveyQuestion', 'qcm_question' => 'App\Entity\Tools\QCM\QCMQuestion'])]
abstract class Question
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
    #[ORM\Column(type: 'boolean')]
    private $canBeSkipped = false;

    public static function getType() {
        $c = get_called_class();
        return $c::TYPE;
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

    /**
     * @return bool
     */
    public function canBeSkipped(): ?bool {
        return $this->canBeSkipped;
    }

    /**
     * @param bool $canBeSkipped
     */
    public function setCanBeSkipped(bool $canBeSkipped): void {
        $this->canBeSkipped = $canBeSkipped;
    }

}
