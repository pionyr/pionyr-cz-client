<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

class EventPreview extends AbstractEvent
{
    public static function createFromResponseData(\stdClass $responseData): self
    {
        $object = new static();

        static::setCommonEventResponseDataToObject($responseData, $object);

        return $object;
    }

    /**
     * @return EventPreview[]
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
