<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Http\RequestManager;
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
        string $expectedBuilderClass,
        string $expectedResponseClass
    ): void {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(RequestMethodInterface::METHOD_GET, $this->isType('string'))
            ->willReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/../Http/Fixtures/articles-response.json'))
            );

        $factory = new RequestBuilderFactory($requestManagerMock);

        /** @var AbstractRequestBuilder $builder */
        $builder = $factory->$factoryMethod();

        $this->assertInstanceOf($expectedBuilderClass, $builder);

        $this->assertInstanceOf($expectedResponseClass, $builder->send());
    }

    /**
     * @return array[]
     */
    public function provideBuilderMethods(): array
    {
        return [
            ['articles', ArticlesRequestBuilder::class, ArticlesResponse::class],
        ];
    }
}
