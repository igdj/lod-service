<?php

namespace LodService\Model;

/**
 * An organization such as a school, NGO, corporation, club, etc.
 *
 * @see http://schema.org/Organization Documentation on Schema.org
 *
 * TODO: There may be multiple precedingOrganization / succeedingOrganization
 *
 */
class Organization
extends SchemaOrg
{
    /**
     * @var string The date that this organization was dissolved.
     */
    protected $dissolutionDate;

    /**
     * @var string The date that this organization was founded.
     */
    protected $foundingDate;

    /**
     * @var Place The place where the organization was founded.
     */
    protected $foundingLocation;

    /**
     * @var Place The location where the organization is located.
     */
    protected $location;

    /**
     * @var Organization The larger organization that this organization is a subOrganization of.
     */
    protected $parentOrganization;

    /**
     * @var Organization The organization that preceded this on.
     */
    protected $precedingOrganization;

    /**
     * @var Organization The organization that suceeded this on.
     */
    protected $succeedingOrganization;

    /**
     * Sets dissolutionDate.
     *
     * @param string $dissolutionDate
     *
     * @return $this
     */
    public function setDissolutionDate($dissolutionDate = null)
    {
        $this->dissolutionDate = self::formatDateIncomplete($dissolutionDate);

        return $this;
    }

    /**
     * Gets dissolutionDate.
     *
     * @return string
     */
    public function getDissolutionDate()
    {
        return $this->dissolutionDate;
    }

    /**
     * Sets foundingDate.
     *
     * @param string $foundingDate
     *
     * @return $this
     */
    public function setFoundingDate($foundingDate = null)
    {
        $this->foundingDate = self::formatDateIncomplete($foundingDate);

        return $this;
    }

    /**
     * Gets foundingDate.
     *
     * @return string
     */
    public function getFoundingDate()
    {
        return $this->foundingDate;
    }

    /**
     * Sets foundingLocation.
     *
     * @param Place $foundingLocation
     *
     * @return $this
     */
    public function setFoundingLocation(Place $foundingLocation = null)
    {
        $this->foundingLocation = $foundingLocation;

        return $this;
    }

    /**
     * Gets foundingLocation.
     *
     * @return Place
     */
    public function getFoundingLocation()
    {
        return $this->foundingLocation;
    }

    /**
     * Sets location.
     *
     * @param Place $location
     *
     * @return $this
     */
    public function setLocation(Place $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Gets location.
     *
     * @return Place
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Sets parentOrganization.
     *
     * @param Organization $parentOrganization
     *
     * @return $this
     */
    public function setParentOrganization(Organization $parentOrganization = null)
    {
        $this->parentOrganizationn = $parentOrganization;

        return $this;
    }

    /**
     * Gets parentOrganization.
     *
     * @return Organization
     */
    public function getParentOrganization()
    {
        return $this->parentOrganization;
    }

    /**
     * Sets precedingOrganization.
     *
     * @param Organization $precedingOrganization
     *
     * @return $this
     */
    public function setPrecedingOrganization(Organization $precedingOrganization = null)
    {
        $this->precedingOrganization = $precedingOrganization;

        return $this;
    }

    /**
     * Gets precedingOrganization.
     *
     * @return Organization
     */
    public function getPrecedingOrganization()
    {
        return $this->precedingOrganization;
    }

    /**
     * Sets succeedingOrganization.
     *
     * @param Organization $succeedingOrganization
     *
     * @return $this
     */
    public function setSucceedingOrganization(Organization $succeedingOrganization = null)
    {
        $this->succeedingOrganization = $succeedingOrganization;

        return $this;
    }

    /**
     * Gets succeedingOrganization.
     *
     * @return Organization
     */
    public function getSucceedingOrganization()
    {
        return $this->succeedingOrganization;
    }
}
