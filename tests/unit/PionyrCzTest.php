<?php declare(strict_types=1);

namespace Pionyr\PionyrCz;

use Http\Mock\Client;
use Pionyr\PionyrCz\Http\Response\ResponseInterface;

/**
 * @covers \Pionyr\PionyrCz\PionyrCz
 */
class PionyrCzTest extends UnitTestCase
{
    /** @test */
    public function shouldBeInstantiable(): void
    {
        $pionyrCz = new PionyrCz('api-token');

        $this->assertInstanceOf(PionyrCz::class, $pionyrCz);
    }

    /** @test */
    public function shouldExecuteRequestViaBuilder(): void
    {
        $mockClient = new Client();
        $mockClient->addResponse($this->createJsonResponseFromFile(__DIR__ . '/Http/Fixtures/articles-response.json'));

        $pionyrCz = new PionyrCz('api-token');
        $pionyrCz->setHttpClient($mockClient);

        $response = $pionyrCz->request()
            ->articles()
            ->send();

        $this->assertCount(1, $mockClient->getRequests());
        $this->assertSame(
            'https://pionyr.cz/api/clanky/?token=api-token',
            $mockClient->getRequests()[0]->getUri()->__toString()
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /** @test */
    public function shouldOverwriteBaseUrlViaRequestManager(): void
    {
        $mockClient = new Client();
        $mockClient->addResponse($this->createJsonResponseFromFile(__DIR__ . '/Http/Fixtures/articles-response.json'));

        $pionyrCz = new PionyrCz('api-token');
        $pionyrCz->setHttpClient($mockClient);
        $pionyrCz->setBaseUrl('http://staging.test/api/');

        $pionyrCz->request()
            ->articles()
            ->send();

        $this->assertSame(
            'http://staging.test/api/clanky/?token=api-token',
            $mockClient->getRequests()[0]->getUri()->__toString()
        );
    }
}
