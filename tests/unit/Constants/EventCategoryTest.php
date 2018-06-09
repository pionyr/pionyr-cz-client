<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Constants;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Pionyr\PionyrCz\Constants\EventCategory
 */
class EventCategoryTest extends TestCase
{
    /** @test */
    public function shouldPrintCategoryDescription(): void
    {
        $category = new EventCategory(EventCategory::TERMIN);

        $this->assertSame('Termín', $category->__toString());
    }
}
