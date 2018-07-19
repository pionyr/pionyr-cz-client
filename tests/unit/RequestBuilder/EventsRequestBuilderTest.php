<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Constants\EventCategory;
use Pionyr\PionyrCz\Entity\EventPreview;
use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\Http\Response\EventsResponse;

/**
 * @covers \Pionyr\PionyrCz\Http\Response\AbstractListResponse
 * @covers \Pionyr\PionyrCz\Http\Response\EventsResponse
 * @covers \Pionyr\PionyrCz\RequestBuilder\AbstractRequestBuilder
 * @covers \Pionyr\PionyrCz\RequestBuilder\EventsRequestBuilder
 */
class EventsRequestBuilderTest extends TestCase
{
    /** @test */
    public function shouldSendRequest(): void
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(RequestMethodInterface::METHOD_GET, '/akce/', [])
            ->willReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/../Http/Fixtures/events-response.json'))
            );

        $builder = new EventsRequestBuilder($requestManagerMock);

        $response = $builder->send();

        $this->assertInstanceOf(EventsResponse::class, $response);
        $this->assertSame(6, $response->getItemTotalCount());
        $this->assertSame(2, $response->getPageCount());
        $this->assertContainsOnlyInstancesOf(EventPreview::class, $response->getData());
    }

    /** @test */
    public function shouldSendRequestWithPageNumberAndCategoryAndDateFromAndDateTo(): void
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->with(
                RequestMethodInterface::METHOD_GET,
                '/akce/',
                [
                    'stranka' => 333,
                    'kategorie' => EventCategory::TABOR,
                    'datumOd' => '2018-09-01',
                    'datumDo' => '2018-12-31',
                ]
            )
            ->willReturn(
                new Response(200, [], file_get_contents(__DIR__ . '/../Http/Fixtures/events-response.json'))
            );

        $builder = new EventsRequestBuilder($requestManagerMock);

        $builder->setPage(333)
            ->setCategory(EventCategory::TABOR())
            ->setDateFrom(new \DateTime('2018-09-01'))
            ->setDateTo(new \DateTime('2018-12-31'))
            ->send();
    }
}
