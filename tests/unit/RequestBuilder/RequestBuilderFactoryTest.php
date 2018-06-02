<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\Http\Response\ArticleResponse;
use Pionyr\PionyrCz\Http\Response\ArticlesResponse;

/**
 * @covers \Pionyr\PionyrCz\RequestBuilder\RequestBuilderFactory
 */
class RequestBuilderFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideBuilderMethods
     */
    public function shouldInstantiateBuilderAndSendRequest(
        string $factoryMethod,
        array $factoryArguments,
        string $expectedBuilderClass,
        string $expectedResponseClass,
        string $responseFile
    ): void {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(RequestMethodInterface::METHOD_GET, $this->isType('string'))
            ->willReturn(
                new Response(200, [], file_get_contents($responseFile))
            );

        $factory = new RequestBuilderFactory($requestManagerMock);

        /** @var AbstractRequestBuilder $builder */
        $builder = $factory->$factoryMethod(...$factoryArguments);

        $this->assertInstanceOf($expectedBuilderClass, $builder);

        $this->assertInstanceOf($expectedResponseClass, $builder->send());
    }

    /**
     * @return array[]
     */
    public function provideBuilderMethods(): array
    {
        return [
            [
                'articles',
                [],
                ArticlesRequestBuilder::class,
                ArticlesResponse::class,
                __DIR__ . '/../Http/Fixtures/articles-response.json',
            ],
            [
                'article',
                ['e7e976ca-2f87-4dc9-bb51-a5fd17ff0905'],
                ArticleRequestBuilder::class,
                ArticleResponse::class,
                __DIR__ . '/../Http/Fixtures/article-response.json',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideUuid
     */
    public function shouldConvertShortUuidToUuidIdentifier(string $passedId, string $expectedUuid): void
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with($this->anything(), $this->anything(), $this->equalTo(['guid' => $expectedUuid]))
            ->willReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/../Http/Fixtures/article-response.json'))
            );

        $factory = new RequestBuilderFactory($requestManagerMock);

        $factory->article($passedId)
            ->send();
    }

    /**
     * @return array[]
     */
    public function provideUuid(): array
    {
        return [
            ['e7e976ca-2f87-4dc9-bb51-a5fd17ff0905', 'e7e976ca-2f87-4dc9-bb51-a5fd17ff0905'],
            ['Nf4YnUiTdBCPvXybAbNwGj', 'e7e976ca-2f87-4dc9-bb51-a5fd17ff0905'],
        ];
    }
}
