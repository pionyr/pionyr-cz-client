<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Constants;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Pionyr\PionyrCz\Constants\EventLocalization
 */
class EventLocalizationTest extends TestCase
{
    /** @test */
    public function shouldPrintLocalizationDescription(): void
    {
        $category = new EventLocalization(EventLocalization::NATIONWIDE);

        $this->assertSame('Celorepubliková', $category->__toString());
    }
}
