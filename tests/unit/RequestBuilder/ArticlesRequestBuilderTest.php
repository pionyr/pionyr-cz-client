<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Constants\ArticleCategory;
use Pionyr\PionyrCz\Http\RequestManager;

/**
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

        $builder->send();
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
            ->setCategory(ArticleCategory::VZDELAVANI())
            ->send();
    }
}
