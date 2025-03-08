<?php

namespace LodService\Provider;

use LodService\Identifier\Identifier;
use LodService\Identifier\GeonamesIdentifier;
use LodService\Identifier\GndIdentifier;
use LodService\Identifier\TgnIdentifier;
use LodService\Identifier\ViafIdentifier;
use LodService\Identifier\WikidataIdentifier;

/**
 * Provider for Wikidata
 *
 * Currently very limited, only supports looking up sameAs identifiers
 */
class WikidataProvider extends AbstractProvider
{
    static $WIKIDATA_IDENTIFIERS = [
        'P227' => 'gnd',
        'P214' => 'viaf',
        'P244' => 'lcauth',
        'P1667' => 'tgn',
        'P1566' => 'geonames',
    ];

    private static function getSparqlClient()
    {
        return new \EasyRdf\Sparql\Client('https://query.wikidata.org/sparql');
    }

    protected $name = 'wikidata';

    public function __construct()
    {
        \LodService\Identifier\Factory::register(GeonamesIdentifier::class);
        \LodService\Identifier\Factory::register(GndIdentifier::class);
        \LodService\Identifier\Factory::register(TgnIdentifier::class);
        \LodService\Identifier\Factory::register(ViafIdentifier::class);
        \LodService\Identifier\Factory::register(WikidataIdentifier::class);
    }

    protected function executeSparqlQuery($query, $sparqlClient = null)
    {
        if (is_null($sparqlClient)) {
            $sparqlClient = self::getSparqlClient();
        }

        return $sparqlClient->query($query);
    }

    public function fetch(Identifier $identifier, $preferredLocale = null)
    {
        throw new \InvalidArgumentException('Not implemented yet');
    }

    protected function lookupQidByProperty($pid, $value, $sparqlClient = null)
    {
        $query = sprintf(
            "SELECT ?wd WHERE { ?wd wdt:%s '%s'. }",
            $pid,
            addslashes($value)
        );

        $result = $this->executeSparqlQuery($query, $sparqlClient);

        $ret = [];
        foreach ($result as $row) {
            $uri = (string) $row->wd;

            if (preg_match('~/(Q\d+)$~', $uri, $matches)) {
                $ret[] = $matches[1];
            }
        }

        return $ret;
    }

    public function lookupSameAs(Identifier $identifier)
    {
        $identifiers = [];

        $name = $identifier->getPrefix();
        if ('wikidata' == $name) {
            $qid = $identifier->getValue();
        }
        else {
            $propertiesByName = array_flip(self::$WIKIDATA_IDENTIFIERS);
            if (array_key_exists($name, $propertiesByName)) {
                $pid = $propertiesByName[$name];
                $qids = $this->lookupQidByProperty($pid, $identifier->getValue());
                if (!empty($qids)) {
                    $qid = $qids[0];

                    $identifiers[] = new WikidataIdentifier($qid);
                }
            }
        }

        if (!empty($qid)) {
            $unionParts = [];
            foreach (self::$WIKIDATA_IDENTIFIERS as $pid => $name) {
                $unionParts[] = sprintf(
                    '{ wd:%s wdt:%s ?property. BIND("%s" as ?propertyId) }',
                    $qid,
                    $pid,
                    $pid
                );
            }

            $query = 'SELECT ?property ?propertyId WHERE {'
                . implode(' UNION ', $unionParts)
                . '}';

            $result = $this->executeSparqlQuery($query);

            foreach ($result as $row) {
                $propertyId = (string) $row->propertyId;
                $propertyValue = $row->property;
                if ($propertyValue instanceof \EasyRdf\Literal) {
                    $propertyValue = $propertyValue->getValue();
                }

                if (!empty($propertyValue)) {
                    $name = self::$WIKIDATA_IDENTIFIERS[$propertyId];
                    if ('lcauth' == $name) {
                        // can be lcsh or lcnaf,
                        if (preg_match('/^sh/', $propertyValue)) {
                            $name = 'lcsh';
                        }
                        else if (preg_match('/^n/', $propertyValue)) {
                            $name = 'lcnaf';
                        }
                    }

                    $identifier = \LodService\Identifier\Factory::byName($name);
                    if (!is_null($identifier)) {
                        $identifier->setValue($propertyValue);

                        $identifiers[] = $identifier;
                    }
                }
            }

            return $identifiers;
        }
    }
}
