<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Helper;

use PHPUnit\Framework\TestCase;

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
}
