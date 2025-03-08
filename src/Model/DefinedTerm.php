<?php

namespace LodService\Model;

/**
 * Pending Schema for DefinedCode.
 *
 * @see https://schema.org/DefinedCode Documentation on Schema.org
 *
 */
class DefinedTerm extends SchemaOrg
{
    /**
     * A DefinedTermSet that contains this term.
     * @Serializer\Type("string")
     */
    protected $inDefinedTermSet = 'https://d-nb.info/standards/elementset/gnd#SubjectHeadingSensoStricto';

    /**
     * A broader term, see
     * https://www.w3.org/2009/08/skos-reference/skos.html#broader
     *
     * @var DefinedTerm|null The broader term.
     */
    protected $broader = null;

    /**
     * @var string|null The start date and time of the item.
     */
    protected $startDate;

    /**
     * @var string|null The end date and time of the item.
     */
    protected $endDate;

    /**
     * Sets broader DefinedTerm.
     *
     * @param DefinedTerm|null $broader
     *
     * @return $this
     */
    public function setBroader(?DefinedTerm $broader = null)
    {
        $this->broader = $broader;

        return $this;
    }

    /**
     * Gets broader DefinedTerm.
     *
     * @return DefinedTerm|null
     */
    public function getBroader()
    {
        return $this->broader;
    }

    /**
     * Sets startDate.
     *
     * @param string|null $startDate
     *
     * @return $this
     */
    public function setStartDate($startDate = null)
    {
        $this->startDate = self::formatDateIncomplete($startDate);

        return $this;
    }

    /**
     * Gets startDate.
     *
     * @return string|null
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Sets endDate.
     *
     * @param string|null $endDate
     *
     * @return $this
     */
    public function setEndDate($endDate = null)
    {
        $this->endDate = self::formatDateIncomplete($endDate);

        return $this;
    }

    /**
     * Gets endDate.
     *
     * @return string|null
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}
