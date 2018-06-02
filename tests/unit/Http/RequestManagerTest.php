<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use Pionyr\PionyrCz\UnitTestCase;

/**
 * @covers \Pionyr\PionyrCz\Http\RequestManager
 */
class RequestManagerTest extends UnitTestCase
{
    /** @test */
    public function shouldSendRequest(): void
    {
        $mockClient = new Client();
        $mockClient->addResponse($this->createJsonResponseFromFile(__DIR__ . '/Fixtures/articles-response.json'));

        $manager = new RequestManager('custom-api-token');
        $manager->setHttpClient($mockClient);

        $response = $manager->sendRequest(RequestMethodInterface::METHOD_GET, '/clanky/', ['foo' => 'b', 'b' => 'c']);

        $this->assertInstanceOf(Response::class, $response);

        $recordedRequests = $mockClient->getRequests();
        $this->assertCount(1, $recordedRequests);

        $this->assertSame(
            'https://pionyr.cz/api/clanky/?foo=b&b=c&token=custom-api-token',
            $recordedRequests[0]->getUri()->__toString()
        );
        $this->assertSame(RequestMethodInterface::METHOD_GET, $recordedRequests[0]->getMethod());
        $this->assertSame(['application/json'], $recordedRequests[0]->getHeader('Accept'));
    }

    /** @test */
    public function shouldSendRequestToCustomBaseUrl(): void
    {
        $mockClient = new Client();
        $mockClient->addResponse($this->createJsonResponseFromFile(__DIR__ . '/Fixtures/articles-response.json'));

        $manager = new RequestManager('custom-api-token');
        $manager->setHttpClient($mockClient);
        $manager->setBaseUrl('http://custom-url.test/api');

        $manager->sendRequest(RequestMethodInterface::METHOD_GET, '/foo/');

        $this->assertSame(
            'http://custom-url.test/api/foo/?token=custom-api-token',
            $mockClient->getLastRequest()->getUri()->__toString()
        );
    }
}
