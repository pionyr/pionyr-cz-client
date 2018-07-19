<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Entity\Group;
use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\Http\Response\GroupsResponse;

/**
 * @covers \Pionyr\PionyrCz\Http\Response\AbstractListResponse
 * @covers \Pionyr\PionyrCz\Http\Response\GroupsResponse
 * @covers \Pionyr\PionyrCz\RequestBuilder\AbstractRequestBuilder
 * @covers \Pionyr\PionyrCz\RequestBuilder\GroupsRequestBuilder
 */
class GroupsRequestBuilderTest extends TestCase
{
    /** @test */
    public function shouldSendRequest(): void
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(RequestMethodInterface::METHOD_GET, '/ps/', [])
            ->willReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/../Http/Fixtures/groups-response.json'))
            );

        $builder = new GroupsRequestBuilder($requestManagerMock);

        $response = $builder->send();

        $this->assertInstanceOf(GroupsResponse::class, $response);
        $this->assertSame(3, $response->getItemTotalCount());
        $this->assertSame(1, $response->getPageCount());
        $this->assertContainsOnlyInstancesOf(Group::class, $response->getData());
    }

    /** @test */
    public function shouldSendRequestWithPageNumber(): void
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(
                RequestMethodInterface::METHOD_GET,
                '/ps/',
                ['stranka' => 333]
            )
            ->willReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/../Http/Fixtures/groups-response.json'))
            );

        $builder = new GroupsRequestBuilder($requestManagerMock);

        $builder->setPage(333)
            ->send();
    }
}
