<?php

namespace App\Form\DataTransformer\Tools\Survey;

use App\Entity\Tools\Survey\SurveyTool;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class SurveyToolToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (surveyTool) to a string (number).
     *
     * @param  SurveyTool|null $surveyTool
     * @return string
     */
    public function transform($surveyTool): mixed
    {
        if (null === $surveyTool) {
            return '';
        }

        return $surveyTool->getId();
    }

    /**
     * Transforms a string (number) to an object (surveyTool).
     *
     * @param  string $surveyToolNumber
     * @return SurveyTool|null
     * @throws TransformationFailedException if object (surveyTool) is not found.
     */
    public function reverseTransform($surveyToolNumber): mixed
    {
        // no surveyTool number? It's optional, so that's ok
        if (!$surveyToolNumber) {
            return null;
        }

        $surveyTool = $this->entityManager
            ->getRepository(SurveyTool::class)
            // query for the issue with this id
            ->find($surveyToolNumber)
        ;

        if (null === $surveyTool) {
            $privateErrorMessage = sprintf('A survey tool with number "%s" does not exist!', $surveyToolNumber);
            $publicErrorMessage = 'The given "{{ value }}" value is not a valid survey tool number.';

            $failure = new TransformationFailedException($privateErrorMessage);
            $failure->setInvalidMessage($publicErrorMessage, [
                '{{ value }}' => $surveyToolNumber,
            ]);

            throw $failure;
        }

        return $surveyTool;
    }
}
