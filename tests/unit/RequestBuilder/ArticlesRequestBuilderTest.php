<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Constants\ArticleCategory;
use Pionyr\PionyrCz\Entity\ArticlePreview;
use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\Http\Response\ArticlesResponse;

/**
 * @covers \Pionyr\PionyrCz\Http\Response\AbstractListResponse
 * @covers \Pionyr\PionyrCz\Http\Response\ArticlesResponse
 * @covers \Pionyr\PionyrCz\RequestBuilder\AbstractRequestBuilder
 * @covers \Pionyr\PionyrCz\RequestBuilder\ArticlesRequestBuilder
 */
class ArticlesRequestBuilderTest extends TestCase
{
    /** @test */
    public function shouldSendRequest(): void
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(RequestMethodInterface::METHOD_GET, '/clanky/', [])
            ->willReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/../Http/Fixtures/articles-response.json'))
            );

        $builder = new ArticlesRequestBuilder($requestManagerMock);

        $response = $builder->send();

        $this->assertInstanceOf(ArticlesResponse::class, $response);
        $this->assertSame(6, $response->getItemTotalCount());
        $this->assertSame(1, $response->getPageCount());
        $this->assertContainsOnlyInstancesOf(ArticlePreview::class, $response->getData());
    }

    /** @test */
    public function shouldSendRequestWithPageNumberAndCategory(): void
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(
                RequestMethodInterface::METHOD_GET,
                '/clanky/',
                ['stranka' => 333, 'kategorie' => ArticleCategory::VZDELAVANI]
            )
            ->willReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/../Http/Fixtures/articles-response.json'))
            );

        $builder = new ArticlesRequestBuilder($requestManagerMock);

        $builder->setPage(333)
            ->setCategory(ArticleCategory::VZDELAVANI)
            ->send();
    }
}
