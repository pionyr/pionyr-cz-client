<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static EventLocalization REGIONAL()
 * @method static EventLocalization NATIONWIDE()
 */
final class EventLocalization extends Enum
{
    public const REGIONAL = '1';
    public const NATIONWIDE = '2';

    /** @var string[] */
    private $descriptions = [
        self::REGIONAL => 'Regionální',
        self::NATIONWIDE => 'Celorepubliková',
    ];

    public function __toString(): string
    {
        return $this->descriptions[$this->getValue()];
    }
}
