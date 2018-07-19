<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Exception\ResponseDecodingException;
use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\RequestBuilder\Fixtures\DummyBuilder;

/**
 * @covers \Pionyr\PionyrCz\Exception\ResponseDecodingException
 * @covers \Pionyr\PionyrCz\RequestBuilder\AbstractRequestBuilder
 */
class AbstractRequestBuilderTest extends TestCase
{
    /** @test */
    public function shouldThrowExceptionWhenDecodingFails(): void
    {
        $requestManager = $this->createRequestManagerMockToReturnFileContents(__DIR__ . '/Fixtures/invalid-json.html');
        $builder = new DummyBuilder($requestManager);

        $this->expectException(ResponseDecodingException::class);
        $this->expectExceptionMessage('Error decoding response');
        $this->expectExceptionMessage('<h1>This is not JSON :-(</h1>');

        $builder->send();
    }

    private function createRequestManagerMockToReturnFileContents(string $filePath)
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())
            ->method('sendRequest')
            ->willReturn(
                new Response(200, [], file_get_contents($filePath))
            );

        return $requestManagerMock;
    }
}
