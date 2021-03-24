<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Exception;

use Fig\Http\Message\RequestMethodInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Pionyr\PionyrCz\Exception\HttpException
 */
class HttpExceptionTest extends TestCase
{
    /** @test */
    public function shouldConstructException(): void
    {
        $request = new Request(RequestMethodInterface::METHOD_GET, 'https://foo.bar/endpoint/');
        $response = new Response(404);
        $previous = new \Exception('previous');

        $exception = new HttpException('exception messsage', $request, $response, $previous);

        $this->assertSame('exception messsage', $exception->getMessage());
        $this->assertSame(404, $exception->getCode());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
