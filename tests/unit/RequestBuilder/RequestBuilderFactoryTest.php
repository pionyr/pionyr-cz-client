<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Http\RequestManager;

class RequestBuilderFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideBuilderMethods
     */
    public function shouldInstantiateBuilderAndSendRequest(
        string $factoryMethod,
        string $expectedBuilderClass
    ): void {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(RequestMethodInterface::METHOD_GET, $this->isType('string'))
            ->willReturn(new Response());

        $factory = new RequestBuilderFactory($requestManagerMock);

        /** @var AbstractRequestBuilder $builder */
        $builder = $factory->$factoryMethod();

        $this->assertInstanceOf($expectedBuilderClass, $builder);

        $this->assertInstanceOf(Response::class, $builder->send());
    }

    /**
     * @return array[]
     */
    public function provideBuilderMethods(): array
    {
        return [
            ['articles', ArticlesRequestBuilder::class],
        ];
    }
}
