<?php

require_once 'bootstrap.inc.php';

use LodService\LodService;
use LodService\Provider\WikidataProvider;
use LodService\Identifier\GndIdentifier;
use LodService\Identifier\TgnIdentifier;
use LodService\Identifier\WikidataIdentifier;

$lodService = new LodService(new WikidataProvider());

$identifiers = [
    new GndIdentifier('118529579'),     // Person
    new WikidataIdentifier('Q26678'),   // Organization
    new TgnIdentifier('7001369'),       // Place
    // new GndIdentifier('4068754-5'),  // DefinedTerm
    // new GndIdentifier('4235034-7'),  // DefinedTerm
];

foreach ($identifiers as $identifier) {
    $resource = $lodService->lookupSameAs($identifier);

    var_dump($resource);
}
