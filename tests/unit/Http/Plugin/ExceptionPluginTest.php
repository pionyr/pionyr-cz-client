<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http\Plugin;

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Promise\FulfilledPromise;
use Http\Promise\Promise;
use Http\Promise\RejectedPromise;
use PHPUnit\Framework\TestCase;
use Pionyr\PionyrCz\Exception\AuthorizationException;
use Pionyr\PionyrCz\Exception\ClientErrorException;
use Pionyr\PionyrCz\Exception\ServerErrorException;
use Psr\Http\Message\RequestInterface;

/**
 * @covers \Pionyr\PionyrCz\Exception\AuthorizationException
 * @covers \Pionyr\PionyrCz\Exception\ClientErrorException
 * @covers \Pionyr\PionyrCz\Exception\ServerErrorException
 * @covers \Pionyr\PionyrCz\Http\Plugin\ExceptionPlugin
 */
class ExceptionPluginTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideSuccessStatusCodes
     */
    public function shouldReturnResponseWhenNoError(int $statusCode): void
    {
        $request = new Request('GET', 'http://pionyr.com/endpoint/');
        $response = new Response($statusCode);

        $next = function (RequestInterface $receivedRequest) use ($request, $response) {
            $this->assertSame($request, $receivedRequest);

            return new FulfilledPromise($response);
        };

        $plugin = new ExceptionPlugin();
        $promise = $plugin->handleRequest($request, $next, function (): Promise {
            return $this->createMock(Promise::class);
        });
        $this->assertInstanceOf(FulfilledPromise::class, $promise);
        $this->assertSame($response, $promise->wait());
    }

    /**
     * @return array[]
     */
    public function provideSuccessStatusCodes(): array
    {
        return [
            'HTTP 200' => [StatusCodeInterface::STATUS_OK],
            'HTTP 201' => [StatusCodeInterface::STATUS_CREATED],
            'HTTP 302' => [StatusCodeInterface::STATUS_FOUND],
        ];
    }

    /**
     * @test
     * @dataProvider provideErrorStatusCodes
     */
    public function shouldThrowExceptionBasedOnStatusCode(int $statusCode, string $expectedExceptionClass): void
    {
        $request = new Request('GET', 'http://pionyr.cz/endpoint/');
        $response = new Response($statusCode);

        $next = function (RequestInterface $receivedRequest) use ($request, $response) {
            $this->assertSame($request, $receivedRequest);

            return new FulfilledPromise($response);
        };

        $plugin = new ExceptionPlugin();

        $promise = $plugin->handleRequest($request, $next, function (): Promise {
            return $this->createMock(Promise::class);
        });
        $this->assertInstanceOf(RejectedPromise::class, $promise);

        $this->expectException($expectedExceptionClass);
        $promise->wait();
    }

    /**
     * @return array[]
     */
    public function provideErrorStatusCodes(): array
    {
        return [
            'HTTP 400' => [StatusCodeInterface::STATUS_BAD_REQUEST, ClientErrorException::class],
            'HTTP 403' => [StatusCodeInterface::STATUS_FORBIDDEN, AuthorizationException::class],
            'HTTP 404' => [StatusCodeInterface::STATUS_NOT_FOUND, ClientErrorException::class],
            'HTTP 500' => [StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR, ServerErrorException::class],
            'Imaginary HTTP 599' => [599, ServerErrorException::class],
        ];
    }
}
