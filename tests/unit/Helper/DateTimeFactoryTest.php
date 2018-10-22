<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Helper;

use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Exception\ResponseDecodingException;

/**
 * @covers \Pionyr\PionyrCz\Helper\DateTimeFactory
 */
class DateTimeFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideDateStrings
     */
    public function shouldCreateDateFromInputString(string $inputString, \DateTimeImmutable $expected): void
    {
        $dateTime = DateTimeFactory::fromInputString($inputString);

        $this->assertEquals($expected, $dateTime);
    }

    /**
     * @return array[]
     */
    public function provideDateStrings(): array
    {
        return [
            ['2018-04-27 13:33:34', new \DateTimeImmutable('2018-04-27 13:33:34')],
            ['2017-01-02 01:03:04', new \DateTimeImmutable('2017-01-02 01:03:04')],
        ];
    }

    /**
     * @test
     * @dataProvider provideDateStrings
     * @dataProvider provideNull
     */
    public function shouldCreateNullableDateFromInputString(?string $inputString, ?\DateTimeImmutable $expected): void
    {
        $dateTime = DateTimeFactory::fromNullableInputString($inputString);

        $this->assertEquals($expected, $dateTime);
    }

    /**
     * @return array[]
     */
    public function provideNull(): array
    {
        return [
            [null, null],
        ];
    }

    /** @test */
    public function shouldThrowExceptionWhenCannotCreateFromFormat(): void
    {
        $this->expectException(ResponseDecodingException::class);
        $this->expectExceptionMessage('Error creating date from string "fooBar"');
        DateTimeFactory::fromInputString('fooBar');
    }
}
