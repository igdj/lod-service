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
     * @var string|null
     */
    protected $additionalType;

    /**
     * @var GeoCoordinates|null The geo coordinates of the place.
     */
    protected $geo;

    /**
     * @var Place|null
     */
    private $containedInPlace;

    /**
     * Sets additionalType.
     *
     * @param string|null $additionalType
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
     * @return string|null
     */
    public function getAdditionalType()
    {
        return $this->additionalType;
    }

    /**
     * Sets geo.
     *
     * @param GeoCoordinates|null $geo
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
     * @return GeoCoordinates|null
     */
    public function getGeo()
    {
        return $this->geo;
    }

    /**
     * Sets containedInPlace.
     *
     * @param Place|null $parent
     *
     * @return $this
     */
    public function setContainedInPlace(?Place $parent = null)
    {
        $this->containedInPlace = $parent;
    }

    /**
     * Gets containedInPlace.
     *
     * @return Place|null
     */
    public function getContainedInPlace()
    {
        return $this->containedInPlace;
    }

    /**
     * Gets all parent Place.
     *
     * @return array
     */
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
