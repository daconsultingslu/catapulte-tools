<?php

namespace App\Entity\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['survey_answer' => 'App\Entity\Tools\Survey\SurveyAnswer', 'qcm_answer' => 'App\Entity\Tools\QCM\QCMAnswer'])]
abstract class Answer
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

    public static function getType() {
        $c = get_called_class();
        return $c::TYPE;
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
    public function isRightAnswer(): ?bool {
        return $this->rightAnswer;
    }

    /**
     * @param bool $rightAnswer
     */
    public function setRightAnswer(bool $rightAnswer): void {
        $this->rightAnswer = $rightAnswer;
    }

}
