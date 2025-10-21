<?php

namespace LodService\Provider;

use LodService\Identifier\Identifier;
use LodService\Identifier\GeonamesIdentifier;
use LodService\Identifier\GndIdentifier;
use LodService\Identifier\LocLdsAgentsIdentifier;
use LodService\Identifier\ViafIdentifier;
use LodService\Identifier\WikidataIdentifier;
use EasyRdf\Resource as EasyRdfResource;

class DnbProvider extends AbstractProvider
{
    // https://wiki.dnb.de/pages/viewpage.action?pageId=449878933
    const ENDPOINT = 'https://sparql.dnb.de/api/gnd';

    protected static function getSparqlClient()
    {
        return new \EasyRdf\Sparql\Client(self::ENDPOINT);
    }

    protected $name = 'dnb';

    public function __construct()
    {
        \LodService\Identifier\Factory::register(GeonamesIdentifier::class);
        \LodService\Identifier\Factory::register(GndIdentifier::class);
        \LodService\Identifier\Factory::register(ViafIdentifier::class);
        \LodService\Identifier\Factory::register(LocLdsAgentsIdentifier::class);
        \LodService\Identifier\Factory::register(WikidataIdentifier::class);

        \EasyRdf\RdfNamespace::set('gndo', 'https://d-nb.info/standards/elementset/gnd#');
        \EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    }

    public function fetch(Identifier $identifier)
    {
        if (!($identifier instanceof GndIdentifier)) {
            throw new \InvalidArgumentException('Expecting a GndIdentifier');
        }

        return $this->fetchEntityFromUri($identifier->toUri(), true);
    }

    protected function fetchEntityFromUri($uri, $fetchRelated = false)
    {
        // $uri . '/about/lds' would give ttl representation
        // see https://www.dnb.de/SharedDocs/Downloads/DE/Professionell/Metadatendienste/linkedDataZugriff.pdf?__blob=publicationFile&v=2
        $rdfUrl = $uri . '/about/rdf';

        try {
            $graph = \EasyRdf\Graph::newAndLoad($rdfUrl);
        }
        catch (\EasyRdf\Http\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
        catch (\EasyRdf\Exception | \Exception $e) {
            // use experimental SPARQL-endpoint as fall back
            $sparql = self::getSparqlClient();
            $query = sprintf('DESCRIBE <%s>', $uri);
            try {
                $graph = $sparql->query($query);
            }
            catch (\EasyRdf\Http\Exception $e) {
                throw new \InvalidArgumentException($e->getMessage());
            }
            catch (\EasyRdf\Exception $e) {
                throw new \RuntimeException($e->getMessage());
            }
        }

        $resource = $graph->resource($uri);

        switch ($type = $resource->get('rdf:type')) {
            case 'https://d-nb.info/standards/elementset/gnd#DifferentiatedPerson':
            case 'https://d-nb.info/standards/elementset/gnd#LiteraryOrLegendaryCharacter':
            case 'https://d-nb.info/standards/elementset/gnd#Pseudonym':
            case 'https://d-nb.info/standards/elementset/gnd#RoyalOrMemberOfARoyalHouse':
                return $this->instantiatePersonFromRdfResource($resource, $fetchRelated);
                break;

            case 'https://d-nb.info/standards/elementset/gnd#CorporateBody':
            case 'https://d-nb.info/standards/elementset/gnd#Company':
            case 'https://d-nb.info/standards/elementset/gnd#MusicalCorporateBody':
            case 'https://d-nb.info/standards/elementset/gnd#ReligiousCorporateBody':
            case 'https://d-nb.info/standards/elementset/gnd#OrganOfCorporateBody':
                return $this->instantiateOrganizationFromRdfResource($resource, $fetchRelated);
                break;

            case 'https://d-nb.info/standards/elementset/gnd#TerritorialCorporateBodyOrAdministrativeUnit':
                // TODO: this can be requested alternatively as Place or Organization
                // e.g. Organization as parentOrganization of http://d-nb.info/gnd/40239-4
                // e.g. Place as placeOfBirth of http://d-nb.info/gnd/118529579
            case 'https://d-nb.info/standards/elementset/gnd#Country':
            case 'https://d-nb.info/standards/elementset/gnd#MemberState':
            case 'https://d-nb.info/standards/elementset/gnd#BuildingOrMemorial':
                return $this->instantiatePlaceFromRdfResource($resource, $fetchRelated);
                break;

            case 'https://d-nb.info/standards/elementset/gnd#SubjectHeading':
            case 'https://d-nb.info/standards/elementset/gnd#SubjectHeadingSensoStricto':
            case 'https://d-nb.info/standards/elementset/gnd#HistoricSingleEventOrEra':
            case 'https://d-nb.info/standards/elementset/gnd#EthnographicName':
            case 'https://d-nb.info/standards/elementset/gnd#NomenclatureInBiologyOrChemistry':
            case 'https://d-nb.info/standards/elementset/gnd#SeriesOfConferenceOrEvent':
                # e.g. Berlinale
                return $this->instantiateDefinedTermFromRdfResource($resource, $fetchRelated);
                break;

            case 'https://d-nb.info/standards/elementset/gnd#ConferenceOrEvent':
                # TODO: e.g. Wiener Kongress, https://d-nb.info/gnd/2026986-9
                // return $this->instantiateEventFromRdfResource($resource, $fetchRelated);
                // break;

            default:
                if (is_null($type)) {
                    return;
                }

                throw new \Exception(sprintf(
                    'No handler for rdf:type %s (%s)',
                    $type,
                    $uri
                ));
        }
    }

    protected function instantiatePersonFromRdfResource(EasyRdfResource $resource, $fetchRelated = false)
    {
        $entity = new \LodService\Model\Person();

        $identifier = new GndIdentifier((string) $resource->get('gndo:gndIdentifier'));
        $entity->setIdentifier($identifier);

        $preferredName = $resource->get('gndo:preferredNameEntityForThePerson');
        if (!is_null($preferredName)) {
            $this->populateEntityFromRdfResource($entity, $preferredName, [
                'gndo:forename' => 'givenName',
                'gndo:surname' => 'familyName',
            ]);
        }

        $this->populateEntityFromRdfResource($entity, $resource, [
            'gndo:dateOfBirth' => 'birthDate',
            'gndo:dateOfDeath' => 'deathDate',
        ]);

        $gender = $resource->get('gndo:gender');
        if (!is_null($gender)) {
            switch ($gender->getUri()) {
                case 'https://d-nb.info/standards/vocab/gnd/gender#female':
                    $this->setEntityValues($entity, [ 'gender' => 'Female' ]);
                    break;

                case 'https://d-nb.info/standards/vocab/gnd/gender#male':
                    $this->setEntityValues($entity, [ 'gender' => 'Male' ]);
                    break;
            }
        }

        // TODO: handle gndo:academicDegree
        // which may either be
        // * An honorific prefix such as Dr.
        // * An honorific suffix such as M.D. /PhD/MSCSW.

        foreach ([
            'gndo:placeOfBirth' => 'birthPlace',
            'gndo:placeOfDeath' => 'deathPlace',
            // TODO: find a way to handle gndo:placeOfActivity
        ] as $key => $property) {
            $subresource = $resource->get($key);
            if (!is_null($subresource)) {
                if ($subresource instanceof \EasyRdf\Resource) {
                    try {
                        $subentity = $this->fetchEntityFromUri($subresource->getUri());
                        if (!is_null($entity)) {
                            $this->setEntityValues($entity, [ $property => $subentity ]);
                        }
                    }
                    catch (\Exception $e) {
                        var_dump($e);
                    }
                }
            }
        }

        // TODO: handle preferredNameForThePerson
        $this->setDisambiguatingDescription($entity, $resource, 'gndo:biographicalOrHistoricalInformation');

        $this->processSameAs($entity, $resource);

        return $entity;
    }

    protected function instantiateOrganizationFromRdfResource($resource, $fetchRelated = false)
    {
        $entity = new \LodService\Model\Organization();

        $identifier = new GndIdentifier((string) $resource->get('gndo:gndIdentifier'));
        $entity->setIdentifier($identifier);

        $this->populateEntityFromRdfResource($entity, $resource, [
            'gndo:preferredNameForTheCorporateBody' => 'name',
            'gndo:homepage' => 'url',
            'gndo:dateOfEstablishment' => 'foundingDate',
            'gndo:dateOfTermination' => 'dissolutionDate',
        ]);

        $subresources = [
            'gndo:placeOfBusiness' => 'location',
        ];

        if ($fetchRelated) {
            // only fetch these for the initial resource in order not to fall into an infinite loop
            $subresources += [
                // 'gndo:hierarchicalSuperiorOfTheCorporateBody' => 'parentOrganization', // TODO: for this, we need to be able to specify the return type Organization
                'gndo:precedingCorporateBody' => 'precedingOrganization',
                'gndo:succeedingCorporateBody' => 'succeedingOrganization',
            ];
        }

        foreach ($subresources as $key => $property) {
            $subresource = $resource->get($key);
            if (!is_null($subresource)) {
                if ($subresource instanceof \EasyRdf\Resource) {
                    try {
                        $subentity = $this->fetchEntityFromUri($subresource->getUri());
                        if (!is_null($subentity)) {
                            $this->setEntityValues($entity, [ $property => $subentity ]);
                        }
                    }
                    catch (\Exception $e) {
                        var_dump($e);
                    }
                }
            }
        }

        $this->setDisambiguatingDescription($entity, $resource, 'gndo:biographicalOrHistoricalInformation');

        $this->processSameAs($entity, $resource);

        return $entity;
    }

    protected function instantiatePlaceFromRdfResource($resource, $fetchRelated = false)
    {
        $entity = new \LodService\Model\Place();

        $identifier = new GndIdentifier((string) $resource->get('gndo:gndIdentifier'));
        $entity->setIdentifier($identifier);

        $this->populateEntityFromRdfResource($entity, $resource, [
            'gndo:preferredNameForThePlaceOrGeographicName' => 'name',
        ]);

        $this->setDisambiguatingDescription($entity, $resource, 'gndo:biographicalOrHistoricalInformation');

        $this->processSameAs($entity, $resource);

        return $entity;
    }

    protected function instantiateDefinedTermFromRdfResource($resource, $fetchRelated = false)
    {
        $entity = new \LodService\Model\DefinedTerm();

        $identifier = new GndIdentifier((string) $resource->get('gndo:gndIdentifier'));
        $entity->setIdentifier($identifier);

        $this->populateEntityFromRdfResource($entity, $resource, [
            'gndo:preferredNameForTheSubjectHeading' => 'name',
            'gndo:dateOfEstablishment' => 'startDate',
            'gndo:dateOfTermination' => 'endDate',
        ]);

        $subresources = [];

        if ($fetchRelated) {
            // only fetch these for the initial resource in order not to fall into an infinite loop
            $subresources += [
                'gndo:broaderTermGeneral' => 'broader',
            ];
        }

        foreach ($subresources as $key => $property) {
            $subresource = $resource->get($key);
            if (!is_null($subresource)) {
                if ($subresource instanceof \EasyRdf\Resource) {
                    try {
                        $subentity = $this->fetchEntityFromUri($subresource->getUri());
                        if (!is_null($subentity)) {
                            $this->setEntityValues($entity, [ $property => $subentity ]);
                        }
                    }
                    catch (\Exception $e) {
                        var_dump($e);
                    }
                }
            }
        }

        $this->setDisambiguatingDescription($entity, $resource, 'gndo:definition');

        $this->processSameAs($entity, $resource);

        return $entity;
    }
}
