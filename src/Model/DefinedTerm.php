<?php

namespace LodService\Model;

/**
 * Pending Schema for DefinedCode.
 *
 * @see https://schema.org/DefinedCode Documentation on Schema.org
 *
 */
class DefinedTerm
extends SchemaOrg
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
     * @var DefinedTerm
     */
    protected $broader = null;

    /**
     * @var string The start date and time of the item.
     */
    protected $startDate;

    /**
     * @var string The end date and time of the item.
     */
    protected $endDate;

    public function setBroader(DefinedTerm $broader)
    {
        $this->broader = $broader;

        return $this;
    }

    public function getBroader()
    {
        return $this->broader;
    }


    /**
     * Sets startDate.
     *
     * @param string $startDate
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
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Sets endDate.
     *
     * @param string $endDate
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
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}
