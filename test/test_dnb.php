<?php

require_once 'bootstrap.inc.php';

use LodService\LodService;
use LodService\Provider\DnbProvider;
use LodService\Identifier\GndIdentifier;

$lodService = new LodService(new DnbProvider());

$gnds = [
    // '118529579',     // Person
    // '107298301X',    // Organization
    // '40239-4',       // Organization with parentOrganization, not handled yet
    // '4068754-5',     // DefinedTerm
    '4235034-7',        // DefinedTerm
];

foreach ($gnds as $gnd) {
    $resource = $lodService->fetch(new GndIdentifier($gnd));

    var_dump($resource);
}
