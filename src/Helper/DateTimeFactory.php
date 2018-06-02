<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Helper;

class DateTimeFactory
{
    public static function fromNullableInputString(?string $inputString): ?\DateTimeImmutable
    {
        if ($inputString === null) {
            return null;
        }

        return static::fromInputString($inputString);
    }

    public static function fromInputString(string $inputString): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $inputString);
    }
}
