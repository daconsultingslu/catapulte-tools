<?php

namespace App\Form\DataTransformer\Tools\QCM;

use App\Entity\Tools\QCM\QCMTool;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class QCMToolToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (qcmTool) to a string (number).
     *
     * @param  QCMTool|null $qcmTool
     * @return string
     */
    public function transform($qcmTool): mixed
    {
        if (null === $qcmTool) {
            return '';
        }

        return $qcmTool->getId();
    }

    /**
     * Transforms a string (number) to an object (qcmTool).
     *
     * @param  string $qcmToolNumber
     * @return QCMTool|null
     * @throws TransformationFailedException if object (qcmTool) is not found.
     */
    public function reverseTransform($qcmToolNumber): mixed
    {
        // no qcmTool number? It's optional, so that's ok
        if (!$qcmToolNumber) {
            return null;
        }

        $qcmTool = $this->entityManager
            ->getRepository(QCMTool::class)
            // query for the issue with this id
            ->find($qcmToolNumber)
        ;

        if (null === $qcmTool) {
            $privateErrorMessage = sprintf('An qcmTool with number "%s" does not exist!', $qcmToolNumber);
            $publicErrorMessage = 'The given "{{ value }}" value is not a valid qcmTool number.';

            $failure = new TransformationFailedException($privateErrorMessage);
            $failure->setInvalidMessage($publicErrorMessage, [
                '{{ value }}' => $qcmToolNumber,
            ]);

            throw $failure;
        }

        return $qcmTool;
    }
}
