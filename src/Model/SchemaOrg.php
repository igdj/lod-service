<?php

namespace LodService\Model;

/**
 * Shared method for Schema.org entities
 *
 * TODO: add alternateName
 *
 */
abstract class SchemaOrg
extends Resource
{
    static function formatDateIncomplete($dateStr)
    {
        if (preg_match('/^v(\d+)$/', $dateStr, $matches)) {
            // GND has v1000
            $dateStr = '-' . $matches[1];
        }

        if (preg_match('/^(\-?)(\d+)$/', $dateStr, $matches)) {
            $dateStr = $matches[1] . sprintf('%04d', $matches[2]) . '-00-00';
        }
        else if (preg_match('/^\-?\d{4}\-\d{2}$/', $dateStr)) {
            $dateStr .= '-00';
        }
        else if (preg_match('/^(\d+)\.(\d+)\.(\d{4})$/', $dateStr, $matches)) {
            $dateStr = join('-', [ $matches[3], $matches[2], $matches[1] ]);
        }

        return $dateStr;
    }

    /**
     * @var string The name of the item.
     */
    protected $name;

    /**
     * @var array An alias for the item..
     */
    protected $alternateName;

    /**
     * @var string
     */
    protected $disambiguatingDescription;

    /**
     * @var string URL of the item.
     */
    protected $url;

    /**
     * Sets name.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * Gets name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets alternateName.
     *
     * @param array $value
     *
     * @return $this
     */
    public function setAlternateName($value = [])
    {
        $this->alternateName = $value;

        return $this;
    }

    /**
     * Adds alternateName.
     *
     * @param string $value
     * @param string|null $key
     *
     * @return $this
     */
    public function addAlternateName($value = null, $key = null)
    {
        if (is_null($key)) {
            if (!is_null($value)) {
                $this->alternateName[] = $value;
            }
        }
        else {
            if (is_null($value)) {
                if (array_key_exists($key, $this->alternateName)) {
                    unset($this->alternateName[$key]);
                }
            }
            else {
                $this->alternateName[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Gets alternateName.
     *
     * @return array
     */
    public function getAlternateName()
    {
        return $this->alternateName;
    }

    /**
     * Sets disambiguating description.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setDisambiguatingDescription($value)
    {
        $this->disambiguatingDescription = $value;

        return $this;
    }

    /**
     * Gets disambiguating description.
     *
     *
     * @param string $lang
     * @return string|null
     */
    public function getDisambiguatingDescription()
    {
        return $this->disambiguatingDescription;
    }

    /**
     * Sets url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Gets url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }
}
