<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Constants\EventCategory;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Pionyr\PionyrCz\Entity\EventPreview
 * @covers \Pionyr\PionyrCz\Entity\AbstractEvent
 */
class EventPreviewTest extends TestCase
{
    /** @test */
    public function shouldCreateEventFromResponseJson(): void
    {
        $responseData = json_decode(file_get_contents(__DIR__ . '/Fixtures/event-preview.json'));

        $event = EventPreview::createFromResponseData($responseData);

        $this->assertSame('Rukodělná dílna', $event->getTitle());
        $this->assertEquals(Uuid::fromString('7743166a-3f07-11e8-9fb0-01155dfe3280'), $event->getUuid());
        $this->assertSame('7743166a-3f07-11e8-9fb0-01155dfe3280', (string) $event->getUuid());
        $this->assertSame('hdAFJnmQ9zeJP5aR8gKWEP', $event->getShortUuid());
        $this->assertSame('<p>Činností <strong>zamířily</strong> i v zájmu infocentra.</p>', $event->getDescription());
        $this->assertEquals(EventCategory::AKCE(), $event->getCategory());
        $this->assertSame('Akce', (string) $event->getCategory());
        $this->assertSame('http://photo.url/goo.jpg', $event->getPhotoUrl());
        $this->assertSame('Pionýrská skupina Karlovy Vary', $event->getOrganizer());
        $this->assertEquals(new \DateTimeImmutable('2018-06-14 17:30:00'), $event->getDateFrom());
        $this->assertEquals(new \DateTimeImmutable('2018-06-14 19:30:00'), $event->getDateTo());
        $this->assertFalse($event->isImportant());
        $this->assertSame('Karlova 336, Karlovy Vary', $event->getPlace());
        $this->assertSame('Karlovarský', $event->getRegion());
        $this->assertSame('https://www.example.com/event/', $event->getUrl());
        $this->assertNull($event->getPriceForMembers());
        $this->assertNull($event->getPriceForMembersDiscounted());
        $this->assertSame('3 Kč', $event->getPriceForPublic());
        $this->assertSame('1 Kč', $event->getPriceForPublicDiscounted());
        $this->assertTrue($event->isNationwide());
        $this->assertEquals(new \DateTimeImmutable('2018-02-14 00:00:00'), $event->getDatePublishFrom());
        $this->assertNull($event->getDatePublishTo());
        $this->assertFalse($event->isShownInCalendar());
        $this->assertTrue($event->isOpenEvent());
        $this->assertSame('Otevřená klubovna', $event->getOpenEventType());
        $this->assertTrue($event->isForKids());
        $this->assertFalse($event->isForLeaders());
        $this->assertTrue($event->isForPublic());
    }

    /** @test */
    public function shouldCreateMultipleEntitiesFromResponseJson(): void
    {
        $responseData = json_decode(file_get_contents(__DIR__ . '/Fixtures/event-preview-list.json'));

        $articles = EventPreview::createFromResponseDataArray((array) $responseData);

        $this->assertCount(2, $articles);
        $this->assertContainsOnlyInstancesOf(EventPreview::class, $articles);
    }
}
