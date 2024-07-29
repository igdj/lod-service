<?php

declare(strict_types=1);

namespace LodService\Provider;

use UtfNormal\Validator;

/**
 * Shared methods for all the providers
 */
abstract class AbstractProvider implements Provider
{
    /**
     * Convert a UTF-8 string to normal form C, canonical composition
     */
    protected static function normalizeString(string $str)
    {
        return Validator::toNFC($str);
    }

    protected $name;

    public function getName()
    {
        return $this->name;
    }

    /**
     * Shared helper methods
     */
    protected function processSameAs($entity, $resource, $property = 'owl:sameAs')
    {
        $resources = $resource->all($property);
        if (!is_null($resources)) {
            foreach ($resources as $resource) {
                $identifier = \LodService\Identifier\Factory::fromUri((string)$resource);

                if (!is_null($identifier)) {
                    $entity->setIdentifier($identifier);
                }
            }
        }
    }

    protected function setEntityValues($entity, $valueMap)
    {
        foreach ($valueMap as $property => $value) {
            $method = 'set' . ucfirst($property);

            if (method_exists($entity, $method)) {
                $entity->$method($value);
            }
            else {
                die($method . ' not found');
            }
        }
    }

    protected function populateEntityFromRdfResource($entity, $resource, $map)
    {
        foreach ($map as $key => $property) {
            $value = $resource->get($key);

            if (!is_null($value)) {
                $method = 'set' . ucfirst($property);

                if (method_exists($entity, $method)) {
                    $entity->$method(self::normalizeString((string)$value));
                }
            }
        }
    }

    protected function setDisambiguatingDescription($entity, $resource, $key)
    {
        $descriptions = $resource->all($key);
        if (!empty($descriptions)) {
            foreach ($descriptions as $description) {
                $entity->setDisambiguatingDescription(self::normalizeString($description->getValue()));
                break;
                /*
                // TODO: switch to multilingual
                $lang = $description->getLang();
                if (!empty($lang)) {
                    $entity->setDisambiguatingDescription($lang, self::normalizeString($description->getValue()));
                }
                */
            }
        }
    }
}
