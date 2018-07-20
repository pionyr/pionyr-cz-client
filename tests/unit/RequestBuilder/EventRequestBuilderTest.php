<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Entity\EventDetail;
use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\Http\Response\EventResponse;
use Ramsey\Uuid\Uuid;

/**
 * @covers \Pionyr\PionyrCz\Http\Response\EventResponse
 * @covers \Pionyr\PionyrCz\RequestBuilder\AbstractRequestBuilder
 * @covers \Pionyr\PionyrCz\RequestBuilder\EventRequestBuilder
 */
class EventRequestBuilderTest extends TestCase
{
    /** @test */
    public function shouldSendRequest(): void
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(
                RequestMethodInterface::METHOD_GET,
                '/akceDetail/',
                ['guid' => '8cecf047-88c0-11e8-8c1c-00155dfe3279']
            )
            ->willReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/../Http/Fixtures/event-response.json'))
            );

        $builder = new EventRequestBuilder(
            $requestManagerMock,
            Uuid::fromString('8cecf047-88c0-11e8-8c1c-00155dfe3279')
        );

        $response = $builder->send();

        $this->assertInstanceOf(EventResponse::class, $response);
        $this->assertInstanceOf(EventDetail::class, $response->getData());
    }
}
