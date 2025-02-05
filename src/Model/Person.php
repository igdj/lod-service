<?php

namespace LodService\Model;

/**
 * A person (alive, dead, undead, or fictional).
 *
 * @see http://schema.org/Person Documentation on Schema.org
 *
 */
class Person
extends SchemaOrg
{
    /**
     * @var string|null Family name. In the U.S., the last name of an Person. This can be used along with givenName instead of the name property.
     */
    protected $familyName;

    /**
     * @var string|null Given name. In the U.S., the first name of a Person. This can be used along with familyName instead of the name property.
     *
     * @Serializer\Type("string")
     */
    protected $givenName;

    /**
     * @var string|null Gender of the person.
     */
    protected $gender;

    /**
     * @var string|null Date of birth.
     */
    protected $birthDate;

    /**
     * @var string|null Date of death.
     */
    protected $deathDate;

    /**
     * @var Place|null The place where the person was born.
     */
    protected $birthPlace;

    /**
     * @var Place|null The place where the person died.
     */
    protected $deathPlace;

    /**
     * Sets familyName.
     *
     * @param string|null $familyName
     *
     * @return $this
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;

        return $this;
    }

    /**
     * Gets familyName.
     *
     * @return string|null
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * Sets givenName.
     *
     * @param string|null $givenName
     *
     * @return $this
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;

        return $this;
    }

    /**
     * Gets givenName.
     *
     * @return string|null
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * Sets gender.
     *
     * @param string|null $gender
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Gets gender.
     *
     * @return string|null
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Sets birthDate.
     *
     * @param string|null $birthDate
     *
     * @return $this
     */
    public function setBirthDate($birthDate = null)
    {
        $this->birthDate = self::formatDateIncomplete($birthDate);

        return $this;
    }

    /**
     * Gets birthDate.
     *
     * @return string|null
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Sets deathDate.
     *
     * @param string|null $deathDate
     *
     * @return $this
     */
    public function setDeathDate($deathDate = null)
    {
        $this->deathDate = self::formatDateIncomplete($deathDate);

        return $this;
    }

    /**
     * Gets deathDate.
     *
     * @return string|null
     */
    public function getDeathDate()
    {
        return $this->deathDate;
    }

    /**
     * Sets birthPlace.
     *
     * @param Place|null $birthPlace
     *
     * @return $this
     */
    public function setBirthPlace(?Place $birthPlace = null)
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    /**
     * Gets birthPlace.
     *
     * @return Place|null
     */
    public function getBirthPlace()
    {
        return $this->birthPlace;
    }

    /**
     * Sets deathPlace.
     *
     * @param Place|null $deathPlace
     *
     * @return $this
     */
    public function setDeathPlace(?Place $deathPlace = null)
    {
        $this->deathPlace = $deathPlace;

        return $this;
    }

    /**
     * Gets deathPlace.
     *
     * @return Place|null
     */
    public function getDeathPlace()
    {
        return $this->deathPlace;
    }
}
