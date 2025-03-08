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
class Organization extends SchemaOrg
{
    /**
     * @var string|null The date that this organization was dissolved.
     */
    protected $dissolutionDate;

    /**
     * @var string|null The date that this organization was founded.
     */
    protected $foundingDate;

    /**
     * @var Place|null The place where the organization was founded.
     */
    protected $foundingLocation;

    /**
     * @var Place|null The location where the organization is located.
     */
    protected $location;

    /**
     * @var Organization|null The larger organization that this organization is a subOrganization of.
     */
    protected $parentOrganization;

    /**
     * @var Organization|null The organization that preceded this on.
     */
    protected $precedingOrganization;

    /**
     * @var Organization|null The organization that suceeded this on.
     */
    protected $succeedingOrganization;

    /**
     * Sets dissolutionDate.
     *
     * @param string|null $dissolutionDate
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
     * @return string|null
     */
    public function getDissolutionDate()
    {
        return $this->dissolutionDate;
    }

    /**
     * Sets foundingDate.
     *
     * @param string|null $foundingDate
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
     * @return string|null
     */
    public function getFoundingDate()
    {
        return $this->foundingDate;
    }

    /**
     * Sets foundingLocation.
     *
     * @param Place|null $foundingLocation
     *
     * @return $this
     */
    public function setFoundingLocation(?Place $foundingLocation = null)
    {
        $this->foundingLocation = $foundingLocation;

        return $this;
    }

    /**
     * Gets foundingLocation.
     *
     * @return Place|null
     */
    public function getFoundingLocation()
    {
        return $this->foundingLocation;
    }

    /**
     * Sets location.
     *
     * @param Place|null $location
     *
     * @return $this
     */
    public function setLocation(?Place $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Gets location.
     *
     * @return Place|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Sets parentOrganization.
     *
     * @param Organization|null $parentOrganization
     *
     * @return $this
     */
    public function setParentOrganization(?Organization $parentOrganization = null)
    {
        $this->parentOrganizationn = $parentOrganization;

        return $this;
    }

    /**
     * Gets parentOrganization.
     *
     * @return Organization|null
     */
    public function getParentOrganization()
    {
        return $this->parentOrganization;
    }

    /**
     * Sets precedingOrganization.
     *
     * @param Organization|null $precedingOrganization
     *
     * @return $this
     */
    public function setPrecedingOrganization(?Organization $precedingOrganization = null)
    {
        $this->precedingOrganization = $precedingOrganization;

        return $this;
    }

    /**
     * Gets precedingOrganization.
     *
     * @return Organization|null
     */
    public function getPrecedingOrganization()
    {
        return $this->precedingOrganization;
    }

    /**
     * Sets succeedingOrganization.
     *
     * @param Organization|null $succeedingOrganization
     *
     * @return $this
     */
    public function setSucceedingOrganization(?Organization $succeedingOrganization = null)
    {
        $this->succeedingOrganization = $succeedingOrganization;

        return $this;
    }

    /**
     * Gets succeedingOrganization.
     *
     * @return Organization|null
     */
    public function getSucceedingOrganization()
    {
        return $this->succeedingOrganization;
    }
}
