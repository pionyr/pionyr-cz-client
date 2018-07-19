<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

class Group extends AbstractUnit
{
    public static function createFromResponseData(\stdClass $responseData): self
    {
        $object = new static();

        static::setCommonUnitResponseDataToObject($responseData, $object);

        return $object;
    }

    /**
     * @return Group[]
     */
    public static function createFromResponseDataArray(array $responseDataArray): array
    {
        $entities = [];
        foreach ($responseDataArray as $responseData) {
            $entities[] = self::createFromResponseData($responseData);
        }

        return $entities;
    }
}
