<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Constants\EventCategory;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Pionyr\PionyrCz\Entity\AbstractEvent
 * @covers \Pionyr\PionyrCz\Entity\EventDetail
 * @covers \Pionyr\PionyrCz\Entity\File
 * @covers \Pionyr\PionyrCz\Entity\IdentifiableTrait
 * @covers \Pionyr\PionyrCz\Entity\Link
 * @covers \Pionyr\PionyrCz\Entity\Photo
 */
class EventDetailTest extends TestCase
{
    /** @test */
    public function shouldCreateMinimalEventDetailFromResponseJson(): void
    {
        $responseData = json_decode(file_get_contents(__DIR__ . '/Fixtures/event-detail-minimal.json'));

        $event = EventDetail::createFromResponseData($responseData);

        $this->assertSame('Participation Island 2018', $event->getTitle());
        $this->assertEquals(Uuid::fromString('8cecf686-88c0-11e8-8c1c-00155dfe3333'), $event->getUuid());
        $this->assertSame('8cecf686-88c0-11e8-8c1c-00155dfe3333', $event->getUuid()->toString());
        $this->assertSame('85sRkup5hiKrLMr8B2JD6T', $event->getShortUuid());
        $this->assertSame('<p>Mezinárodní tábor spřátelené organizace ve Finsku.</p>', $event->getDescription());
        $this->assertEquals(EventCategory::MEZINARODNI(), $event->getCategory());
        $this->assertSame('Mezinárodní', (string) $event->getCategory());
        $this->assertNull($event->getPhotoUrl());
        $this->assertSame('Pionýr, z. s.', $event->getOrganizer());
        $this->assertEquals(new \DateTimeImmutable('2018-07-25 06:00:00'), $event->getDateFrom());
        $this->assertEquals(new \DateTimeImmutable('2018-08-01 18:00:00'), $event->getDateTo());
        $this->assertFalse($event->isImportant());
        $this->assertSame('Bengtsår Camp Island, Hanko, Finland', $event->getPlace());
        $this->assertSame('Zahraničí', $event->getRegion());
        $this->assertSame('', $event->getWebsiteUrl());
        $this->assertNull($event->getPriceForMembers());
        $this->assertNull($event->getPriceForMembersDiscounted());
        $this->assertNull($event->getPriceForPublic());
        $this->assertNull($event->getPriceForPublicDiscounted());
        $this->assertFalse($event->isNationwide());
        $this->assertNull($event->getDatePublishFrom());
        $this->assertNull($event->getDatePublishTo());
        $this->assertTrue($event->isShownInCalendar());
        $this->assertFalse($event->isOpenEvent());
        $this->assertNull($event->getOpenEventType());
        $this->assertTrue($event->isForKids());
        $this->assertTrue($event->isForLeaders());
        $this->assertFalse($event->isForPublic());

        $this->assertNull($event->getAgeFrom());
        $this->assertNull($event->getAgeTo());
        $this->assertNull($event->getExpectedNumberOfParticipants());
        $this->assertNull($event->getTransportation());
        $this->assertNull($event->getAccommodation());
        $this->assertNull($event->getFood());
        $this->assertSame('', $event->getRequiredEquipment());

        $this->assertSame([], $event->getPhotos());
        $this->assertSame([], $event->getFiles());
        $this->assertSame([], $event->getLinks());
    }

    /** @test */
    public function shouldCreateFullEventDetailFromResponseJson(): void
    {
        $responseData = json_decode(file_get_contents(__DIR__ . '/Fixtures/event-detail-full.json'));

        $event = EventDetail::createFromResponseData($responseData);

        $this->assertSame('LTŠ rukodělná Zelená Lhota', $event->getTitle());
        $this->assertEquals(Uuid::fromString('8cec671b-88c0-11e8-8c1c-00155dfe3279'), $event->getUuid());
        $this->assertSame('8cec671b-88c0-11e8-8c1c-00155dfe3279', $event->getUuid()->toString());
        $this->assertSame('z95uRC4pSQuXnWsQ6u2C6T', $event->getShortUuid());
        $this->assertSame('<p>Jedná se o rukodělný týden.</p>', $event->getDescription());
        $this->assertEquals(EventCategory::VZDELAVACI(), $event->getCategory());
        $this->assertSame('Vzdělávací', (string) $event->getCategory());
        $this->assertSame('https://pionyr.cz/1516697984_P8187356.JPG', $event->getPhotoUrl());
        $this->assertSame('04 Plzeňská KOP', $event->getOrganizer());
        $this->assertEquals(new \DateTimeImmutable('2018-08-12 12:00:00'), $event->getDateFrom());
        $this->assertEquals(new \DateTimeImmutable('2018-08-17 17:00:00'), $event->getDateTo());
        $this->assertTrue($event->isImportant());
        $this->assertSame('Zelená Lhota', $event->getPlace());
        $this->assertSame('Plzeňský', $event->getRegion());
        $this->assertSame('https://web.cz', $event->getWebsiteUrl());
        $this->assertSame('900 Kč', $event->getPriceForMembers());
        $this->assertSame('800 Kč', $event->getPriceForMembersDiscounted());
        $this->assertSame('2000 Kč', $event->getPriceForPublic());
        $this->assertSame('1500 Kč', $event->getPriceForPublicDiscounted());
        $this->assertTrue($event->isNationwide());
        $this->assertEquals(new \DateTimeImmutable('2018-01-22 00:00:00'), $event->getDatePublishFrom());
        $this->assertEquals(new \DateTimeImmutable('2018-09-01 00:00:00'), $event->getDatePublishTo());
        $this->assertTrue($event->isShownInCalendar());
        $this->assertFalse($event->isOpenEvent());
        $this->assertNull($event->getOpenEventType());
        $this->assertFalse($event->isForKids());
        $this->assertTrue($event->isForLeaders());
        $this->assertFalse($event->isForPublic());

        $this->assertSame(15, $event->getAgeFrom());
        $this->assertSame(99, $event->getAgeTo());
        $this->assertSame(25, $event->getExpectedNumberOfParticipants());
        $this->assertSame('každý se dopravuje na vlastní náklady', $event->getTransportation());
        $this->assertSame('v pokojích po 10 lidech na palandách', $event->getAccommodation());
        $this->assertSame('zajištěna po celou dobu konání akce včetně pitného režimu i kávy', $event->getFood());
        $this->assertSame('spacák, přezůvky, pohodlné oblečení, vlastní nářadí', $event->getRequiredEquipment());

        $photos = $event->getPhotos();
        $this->assertCount(3, $photos);
        $this->assertContainsOnlyInstancesOf(Photo::class, $photos);
        $this->assertSame('https://pionyr.cz/1.jpg', $photos[0]->getUrl());
        $this->assertSame('Prvni fotka', $photos[0]->getTitle());
        $this->assertSame('https://pionyr.cz/3.jpg', $photos[2]->getUrl());
        $this->assertSame('', $photos[2]->getTitle());

        $files = $event->getFiles();
        $this->assertCount(2, $files);
        $this->assertContainsOnlyInstancesOf(File::class, $files);
        $this->assertSame('https://pionyr.cz/pozvanka.pdf', $files[0]->getUrl());
        $this->assertSame('Pozvánka', $files[0]->getTitle());
        $this->assertTrue($files[0]->isPublic());
        $this->assertSame('https://pionyr.cz/interni.pdf', $files[1]->getUrl());
        $this->assertSame('Neveřejný dokument', $files[1]->getTitle());
        $this->assertFalse($files[1]->isPublic());

        $links = $event->getLinks();
        $this->assertCount(2, $links);
        $this->assertContainsOnlyInstancesOf(Link::class, $links);
        $this->assertSame('http://www.ledovapraha.cz', $links[0]->getUrl());
        $this->assertSame('Ledová praha', $links[0]->getTitle());
        $this->assertSame('www.google.cz', $links[1]->getUrl());
        $this->assertSame('', $links[1]->getTitle());
    }
}
