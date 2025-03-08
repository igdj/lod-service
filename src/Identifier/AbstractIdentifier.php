<?php

namespace LodService\Identifier;

abstract class AbstractIdentifier implements Identifier
{
    protected $name;
    protected $prefix = null;
    protected $value;

    /**
     * Instantiate identifier, optionally from $value
     */
    public function __construct($value = null)
    {
        if (!is_null($value)) {
            if (method_exists($this, 'setValueFromUri')) {
                $this->setValueFromUri($value);
            }
            else if (method_exists($this, 'setValueFromUrn')) {
                $this->setValueFromUrn($value);
            }
            else {
                $this->setValue($value);
            }
        }
    }

    /**
     * Gets name of the identifier.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * We want different names like lcsh, lccn map to the same prefix lcauth
     */
    public function getPrefix()
    {
        if (empty($this->prefix)) {
            return $this->getName();
        }

        return $this->prefix;
    }

    /**
     * Sets value.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Gets value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Prepend name to indicate concrete identifier.
     *
     * @return string
     */
    public function __toString()
    {
        return join(':', [ $this->name, $this->value ]);
    }
}
