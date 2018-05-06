<?php declare(strict_types=1);

namespace Pionyr\PionyrCz;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Pionyr\PionyrCz\PionyrCz
 */
class PionyrCzTest extends TestCase
{
    /** @test */
    public function shouldBeInstantiable(): void
    {
        $pionyrCz = new PionyrCz('api-token');

        $this->assertInstanceOf(PionyrCz::class, $pionyrCz);
    }
}
