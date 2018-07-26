<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Constants;

use MyCLabs\Enum\Enum;

/**
 * Constants for the most common article categories.
 * Note this is not complete list nor enum - categories are user-editable, thus may change anytime.
 */
final class ArticleCategory
{
    public const AKCE_A_SOUTEZE = 1;
    public const EKONOMIKA = 2;
    public const MEZINARODNI = 3;
    public const PROGRAM_A_HRY = 4;
    public const VZDELAVANI = 5;
    public const OSTATNI = 6;
    public const SETKANI = 7;
    public const UVODNI_NOVINKY = 9;
    public const JMKOP = 12;
    public const PTO = 13;

    private function __construct()
    {
    }
}
