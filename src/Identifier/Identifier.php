<?php

namespace LodService\Identifier;

interface Identifier
{
    public function getValue();
    public function setValue($value);

    public function getName();
}
