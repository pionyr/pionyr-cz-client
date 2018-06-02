<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static ArticleCategory AKCE_A_SOUTEZE()
 * @method static ArticleCategory EKONOMIKA()
 * @method static ArticleCategory MEZINARODNI()
 * @method static ArticleCategory PROGRAM_A_HRY()
 * @method static ArticleCategory VZDELAVANI()
 * @method static ArticleCategory OSTATNI()
 * @method static ArticleCategory SETKANI()
 */
final class ArticleCategory extends Enum
{
    public const AKCE_A_SOUTEZE = 1;
    public const EKONOMIKA = 2;
    public const MEZINARODNI = 3;
    public const PROGRAM_A_HRY = 4;
    public const VZDELAVANI = 5;
    public const OSTATNI = 6;
    public const SETKANI = 7;

    /** @var string[] */
    private $descriptions = [
        self::AKCE_A_SOUTEZE => 'Akce a soutěže',
        self::EKONOMIKA => 'Ekonomika',
        self::MEZINARODNI => 'Mezinárodní',
        self::PROGRAM_A_HRY => 'Program a hry',
        self::VZDELAVANI => 'Vzdělávání',
        self::OSTATNI => 'Ostatní',
        self::SETKANI => 'Setkání',
    ];

    public function __toString(): string
    {
        return $this->descriptions[$this->getValue()];
    }
}
