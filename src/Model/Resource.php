<?php

namespace LodService\Model;

use LodService\Identifier\Identifier;


/**
 * Common Base class
 *
 */
abstract class Resource
{
    /**
     * @var array
     */
    protected $identifiers = [];

    /**
     * Sets identifier.
     *
     * @return $this
     */
    public function setIdentifier(Identifier $identifier)
    {
        $this->identifiers[$identifier->getName()] = $identifier;

        return $this;
    }

    /**
     * Gets identifier.
     *
     * @return string|null
     */
    public function getIdentifier($name)
    {
        return array_key_exists($name, $this->identifiers)
            ? $this->identifiers[$name]
            : null;
    }
}
