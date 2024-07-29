<?php

namespace LodService\Provider;

use LodService\Identifier\Identifier;
use LodService\Identifier\TgnIdentifier;

/**
 * Provider for Getty Vocabulary Services
 */
class GettyVocabulariesProvider
extends AbstractProvider
{
    const ENDPOINT = 'http://vocab.getty.edu/sparql.json';

    protected $name = 'getty';

    /**
     * Fetch entity by identifier through Getty Vocabulary Services
     *
     * Currently only implemented for fetching Place-entities by
     * TGN-identifiers.
     *
     * TODO:
     *  Fetch Person-entities by ULAN-identifiers as well
     */
    public function fetch(Identifier $identifier, $preferredLocale = null)
    {
        if ($identifier instanceof TgnIdentifier) {
            return $this->fetchPlaceEntity($identifier);
        }

        throw new \InvalidArgumentException('Expecting a TgnIdentifier');
    }

    /**
     * Fetch Place-entity by TGN-identifier
     */
    protected function fetchPlaceEntity(TgnIdentifier $identifier, $preferredLocale = null)
    {
        $sparql = new \EasyRdf\Sparql\Client(self::ENDPOINT);

        $uri = sprintf('tgn:%d', $tgn = $identifier->getValue());

        // for optional english-label see http://vocab.getty.edu/queries#Places_with_English_or_GVP_Label
        // TODO: get sameAs
        // TODO: don't hardwire alternate locales en / de

        $query = <<<EOT
SELECT ?Subject ?name ?name_en ?name_de ?type ?isoalpha3 ?parent ?parentString ?ancestor ?ancestorIsoalpha3 ?latitude ?longitude
{
    BIND({$uri} as ?Subject)

    ?Subject a gvp:Subject;

    gvp:prefLabelGVP/xl:literalForm ?name;

    gvp:placeTypePreferred/gvp:prefLabelGVP/xl:literalForm ?type;

    gvp:parentString ?parentString.

    OPTIONAL {
        ?Subject
            xl:prefLabel [
                xl:literalForm ?name_en;
                dct:language gvp_lang:en
            ]
    }

    OPTIONAL {
        ?Subject
            xl:prefLabel [
                xl:literalForm ?name_de;
                dct:language gvp_lang:de
            ]
    }

    OPTIONAL {
        ?Subject
            foaf:focus [
                wgs:lat ?latitude;
                wgs:long ?longitude
            ]
    }.

    OPTIONAL {
        ?Subject xl:altLabel ?altLabel.
        ?altLabel gvp:termKind <http://vocab.getty.edu/term/kind/ISOalpha3>;
            gvp:term ?isoalpha3.
    }.

    OPTIONAL {
        ?Subject
            gvp:broaderPreferred ?parent.
    }.

    OPTIONAL {
        ?Subject gvp:broaderPreferred+ ?ancestor.

        ?ancestor xl:altLabel ?altLabel.

        ?altLabel gvp:termKind <http://vocab.getty.edu/term/kind/ISOalpha3>;
            gvp:term ?ancestorIsoalpha3.
    }
}

EOT;

        $entity = null;

        $result = $sparql->query($query);
        if (count($result) > 0) {
            foreach ($result as $row) {
                $entity = new \LodService\Model\Place();
                $entity->setIdentifier($identifier);

                $geoCoordinates = new \LodService\Model\GeoCoordinates();
                $geoCoordinatesSet = false;

                foreach ([
                    'name' => 'name',
                    'name_en' => 'addAlternateName',
                    'name_de' => 'addAlternateName',
                    'type' => 'additionalType',
                    'latitude' => 'latitude',
                    'longitude' => 'longitude',
                ] as $src => $target)
                {
                    if (property_exists($row, $src)) {
                        $property = $row->$src;
                        $method = 'set' . ucfirst($target);

                        switch ($target) {
                            case 'longitude':
                            case 'latitude':
                                $geoCoordinates->$method((string)$property);
                                $geoCoordinatesSet = true;
                                break;

                            default:
                                if (preg_match('/^name_([a-z]+)$/', $src, $matches)) {
                                    $entity->$target((string)$property, $matches[1]);
                                }
                                else {
                                    $entity->$method((string)$property);
                                }
                        }
                    }
                }

                if (property_exists($row, 'parent')) {
                    $parentIdentifier = new TgnIdentifier((string)($row->parent));

                    $parent = new \LodService\Model\Place();
                    $parent->setIdentifier($parentIdentifier);

                    $entity->setContainedInPlace($parent);
                }

                $code = null;
                $isoAlpha2 = null;
                if (property_exists($row, 'isoalpha3')) {
                    $code = (string)($row->isoalpha3);
                }

                if (empty($code) && property_exists($row, 'ancestorIsoalpha3')) {
                    $code = (string)($row->ancestorIsoalpha3);
                }

                if (!empty($code)) {
                    $iso3166 = new \League\ISO3166\ISO3166();
                    try {
                        $data = $iso3166->alpha3($code);
                        if (!empty($data['alpha2'])) {
                            $isoAlpha2 = $data['alpha2'];
                        }
                    }
                    catch (\Exception $e) {
                        ; // ignore
                    }
                }

                if (!empty($isoAlpha2)) {
                    $geoCoordinates->setAddressCountry($isoAlpha2);
                    $geoCoordinatesSet = true;
                }

                if ($geoCoordinatesSet) {
                    $entity->setGeo($geoCoordinates);
                }

                break; // only pickup first $result
            }
        }

        return $entity;
    }
}
