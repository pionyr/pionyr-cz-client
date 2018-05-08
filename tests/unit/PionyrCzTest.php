<?php declare(strict_types=1);

namespace Pionyr\PionyrCz;

/**
 * @covers \Pionyr\PionyrCz\PionyrCz
 */
class PionyrCzTest extends UnitTestCase
{
    /** @test */
    public function shouldBeInstantiable(): void
    {
        $pionyrCz = new PionyrCz('api-token');

        $this->assertInstanceOf(PionyrCz::class, $pionyrCz);
    }
}
