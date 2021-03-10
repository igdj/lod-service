<?php

require_once 'bootstrap.inc.php';

use LodService\LodService;
use LodService\Provider\GettyVocabulariesProvider;
use LodService\Identifier\TgnIdentifier;

$lodService = new LodService(new GettyVocabulariesProvider());

$identifiers = [
    // new TgnIdentifier('7029392'),     // World
    new TgnIdentifier('7003669'),     // Bavaria
];

foreach ($identifiers as $identifier) {
    $resource = $lodService->fetch($identifier);

    var_dump($resource);
}
