<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http\Plugin;

use Fig\Http\Message\StatusCodeInterface;
use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Pionyr\PionyrCz\Exception\AuthorizationException;
use Pionyr\PionyrCz\Exception\ClientErrorException;
use Pionyr\PionyrCz\Exception\ServerErrorException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ExceptionPlugin implements Plugin
{
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        /** @var Promise $promise */
        $promise = $next($request);

        return $promise->then(function (ResponseInterface $response) use ($request) {
            return $this->transformResponseToException($request, $response);
        });
    }

    private function transformResponseToException(
        RequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $responseCode = $response->getStatusCode();

        if ($responseCode === StatusCodeInterface::STATUS_FORBIDDEN) { // Yes, they use 403 Forbidden instead of 401...
            throw AuthorizationException::fromRequestAndResponse($request, $response);
        }

        if ($responseCode >= 400 && $responseCode < 500) {
            throw new ClientErrorException($response->getReasonPhrase(), $request, $response);
        }

        if ($responseCode >= 500 && $responseCode < 600) {
            throw new ServerErrorException($response->getReasonPhrase(), $request, $response);
        }

        return $response;
    }
}
