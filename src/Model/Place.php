<?php

namespace LodService\Model;

/**
 * Entities that have a somewhat fixed, physical extension.
 *
 * @see http://schema.org/Place Documentation on Schema.org
 *
 */
class Place
extends SchemaOrg
{
    /**
     * @var string
     */
    protected $additionalType;

    /**
     * @var GeoCoordinates The geo coordinates of the place.
     */
    protected $geo;

    /**
     * @var Place
     */
    private $containedInPlace;

    /**
     * Sets additionalType.
     *
     * @param string $additionalType
     *
     * @return $this
     */
    public function setAdditionalType($additionalType)
    {
        $this->additionalType = $additionalType;

        return $this;
    }

    /**
     * Gets additionalType.
     *
     * @return string
     */
    public function getAdditionalType()
    {
        return $this->additionalType;
    }

    /**
     * Sets geo.
     *
     * @param GeoCoordinates $geo
     *
     * @return $this
     */
    public function setGeo($geo)
    {
        $this->geo = $geo;

        return $this;
    }

    /**
     * Gets geo.
     *
     * @return GeoCoordinates
     */
    public function getGeo()
    {
        return $this->geo;
    }

    public function setContainedInPlace(Place $parent = null)
    {
        $this->containedInPlace = $parent;
    }

    public function getContainedInPlace()
    {
        return $this->containedInPlace;
    }

    public function getPath()
    {
        $path = [];
        $parent = $this->getContainedInPlace();
        while ($parent != null) {
            $path[] = $parent;
            $parent = $parent->getContainedInPlace();
        }

        return array_reverse($path);
    }
}
