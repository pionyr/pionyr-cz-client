<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Helper;

use Pionyr\PionyrCz\Exception\ResponseDecodingException;

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
        $date = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $inputString);

        if ($date === false) {
            throw new ResponseDecodingException(sprintf('Error creating date from string "%s"', $inputString));
        }

        return $date;
    }
}
