<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Exception thrown when request authentication fails.
 */
class AuthorizationException extends ClientErrorException
{
    /** @return static */
    public static function fromRequestAndResponse(
        RequestInterface $request,
        ResponseInterface $response,
        ?\Throwable $previous = null
    ): self {
        $responseData = json_decode($response->getBody()->getContents());

        $message = sprintf(
            'Authentication failed, token is probably invalid%s',
            isset($responseData->message) ? ' (' . $responseData->message . ')' : ''
        );

        return new static($message, $request, $response, $previous);
    }
}
