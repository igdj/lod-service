<?php

namespace LodService\Provider;

use LodService\Identifier\Identifier;

interface Provider
{
    public function fetch(Identifier $identifier);

    public function getName();
}
