<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Constants\ArticleCategory;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Pionyr\PionyrCz\Entity\ArticleDetail
 * @covers \Pionyr\PionyrCz\Entity\AbstractArticle
 * @covers \Pionyr\PionyrCz\Entity\Photo
 */
class ArticleDetailTest extends TestCase
{
    /** @test */
    public function shouldCreateMinimalArticleDetailFromResponseJson(): void
    {
        $responseData = json_decode(file_get_contents(__DIR__ . '/Fixtures/article-detail-minimal.json'));

        $article = ArticleDetail::createFromResponseData($responseData);

        $this->assertSame('Aktualita', $article->getTitle());
        $this->assertEquals(Uuid::fromString('635124ec-3d05-447d-acc4-2a87d7a711a3'), $article->getUuid());
        $this->assertSame('635124ec-3d05-447d-acc4-2a87d7a711a3', (string) $article->getUuid());
        $this->assertSame('5dQgVtfyTfje6wXfRyjEgK', $article->getShortUuid());
        $this->assertEquals(new \DateTimeImmutable('2018-04-27 13:33:36'), $article->getDatePublished());
        $this->assertEquals(new ArticleCategory(ArticleCategory::AKCE_A_SOUTEZE), $article->getCategory());
        $this->assertSame('Akce a soutěže', (string) $article->getCategory());
        $this->assertSame('John Doe', $article->getAuthorName());
        $this->assertSame('<p>Perex</p>', $article->getPerex());
        $this->assertNull($article->getPerexPhotoUrl());
        $this->assertSame('', $article->getText());
        $this->assertNull($article->getTextPhotoUrl());
        $this->assertEquals(new \DateTimeImmutable('2018-04-27 14:15:16'), $article->getDateShowFrom());
        $this->assertNull($article->getDateShowTo());
        $this->assertTrue($article->isNews());
        $this->assertFalse($article->isNewsForMembersPublic());
        $this->assertFalse($article->isNewsForMembersPrivate());
        $this->assertFalse($article->isMyRegion());
        $this->assertFalse($article->isMozaika());
        $this->assertFalse($article->isOfferedToOtherRegions());

        $this->assertSame([], $article->getPhotos());
    }

    /** @test */
    public function shouldCreateFullArticleDetailFromResponseJson(): void
    {
        $responseData = json_decode(file_get_contents(__DIR__ . '/Fixtures/article-detail-full.json'));

        $article = ArticleDetail::createFromResponseData($responseData);

        $this->assertSame('Dračí smyčka 2018', $article->getTitle());
        $this->assertEquals(Uuid::fromString('04840dab-2dc6-11e8-9fb0-00155dfe3280'), $article->getUuid());
        $this->assertSame('04840dab-2dc6-11e8-9fb0-00155dfe3280', (string) $article->getUuid());
        $this->assertSame('6Ri3Yt94oC7M4KsxZaToo', $article->getShortUuid());
        $this->assertSame('2018-02-15 13:33:36', $article->getDatePublished()->format('Y-m-d H:i:s'));
        $this->assertEquals(new ArticleCategory(ArticleCategory::OSTATNI), $article->getCategory());
        $this->assertSame('Ostatní', (string) $article->getCategory());
        $this->assertSame('John Doe', $article->getAuthorName());
        $this->assertSame(
            '<p>V sobotu 24. února se uskuteční tradiční akce brněnských pionýrů</p>',
            $article->getPerex()
        );
        $this->assertSame('http://photo.url/goo.jpg', $article->getPerexPhotoUrl());
        $this->assertSame(
            '<p>Úkolem závodníků nejrůznějších věkových kategorií je uvázat určené uzly.</p>',
            $article->getText()
        );
        $this->assertSame('http://photo.url/gooText.jpg', $article->getTextPhotoUrl());
        $this->assertEquals(new \DateTimeImmutable('2018-02-01 01:02:03'), $article->getDateShowFrom());
        $this->assertEquals(new \DateTimeImmutable('2018-06-30 04:05:06'), $article->getDateShowTo());
        $this->assertTrue($article->isNews());
        $this->assertTrue($article->isNewsForMembersPublic());
        $this->assertTrue($article->isNewsForMembersPrivate());
        $this->assertTrue($article->isMyRegion());
        $this->assertTrue($article->isMozaika());
        $this->assertTrue($article->isOfferedToOtherRegions());

        $photos = $article->getPhotos();
        $this->assertCount(3, $photos);
        $this->assertContainsOnlyInstancesOf(Photo::class, $photos);
        $this->assertSame('https://pionyr.cz/1.jpg', $photos[0]->getUrl());
        $this->assertSame('Prvni fotka', $photos[0]->getTitle());
        $this->assertSame('https://pionyr.cz/3.jpg', $photos[2]->getUrl());
        $this->assertSame('', $photos[2]->getTitle());

    }
}
