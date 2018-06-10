<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Http\RequestManager;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Pionyr\PionyrCz\RequestBuilder\AbstractRequestBuilder
 * @covers \Pionyr\PionyrCz\RequestBuilder\ArticleRequestBuilder
 */
class ArticleRequestBuilderTest extends TestCase
{
    /** @test */
    public function shouldSendRequest(): void
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(
                RequestMethodInterface::METHOD_GET,
                '/clanekDetail/',
                ['guid' => '04840dab-2dc6-11e8-9fb0-00155dfe3280']
            )
            ->willReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/../Http/Fixtures/article-response.json'))
            );

        $builder = new ArticleRequestBuilder(
            $requestManagerMock,
            Uuid::fromString('04840dab-2dc6-11e8-9fb0-00155dfe3280')
        );

        $builder->send();
    }
}
