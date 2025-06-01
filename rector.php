<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;

return static function (RectorConfig $rectorConfig): void {
    // Define the paths to process
    $rectorConfig->paths([
        __DIR__ . '/src/Entity',
    ]);

    // Include the Doctrine annotations to attributes rule set
    $rectorConfig->import(DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES);
};