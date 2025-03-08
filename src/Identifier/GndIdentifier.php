<?php

namespace LodService\Identifier;

class GndIdentifier extends UriIdentifier
{
    protected $name = 'gnd';
    protected $baseUri = 'https://d-nb.info/gnd/';
    protected $baseUriVariants = [ 'http://d-nb.info/gnd/' ];
}
