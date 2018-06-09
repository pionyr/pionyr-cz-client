<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static EventCategory JEDNANI_ORGANU()
 * @method static EventCategory TERMIN()
 * @method static EventCategory MEZINARODNI()
 * @method static EventCategory VZDELAVACI()
 * @method static EventCategory TABOR()
 * @method static EventCategory AKCE()
 */
final class EventCategory extends Enum
{
    public const JEDNANI_ORGANU = 1;
    public const TERMIN = 2;
    public const MEZINARODNI = 3;
    public const VZDELAVACI = 4;
    public const TABOR = 5;
    public const AKCE = 6;

    /** @var string[] */
    private $descriptions = [
        self::JEDNANI_ORGANU => 'Jednání orgánů',
        self::TERMIN => 'Termín',
        self::MEZINARODNI => 'Mezinárodní',
        self::VZDELAVACI => 'Vzdělávací',
        self::TABOR => 'Tábor',
        self::AKCE => 'Akce',
    ];

    public function __toString(): string
    {
        return $this->descriptions[$this->getValue()];
    }
}
