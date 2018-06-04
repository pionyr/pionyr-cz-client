<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Exception thrown when response cannot be decoded.
 */
class ResponseDecodingException extends \RuntimeException implements PionyrCzExceptionInterface
{
    /** @return static */
    public static function forJsonError(string $jsonErrorMsg, ResponseInterface $response): self
    {
        return new static(
            sprintf(
                "Error decoding response: %s\n\nStatus code: %s %s\nBody:\n%s",
                $jsonErrorMsg,
                $response->getStatusCode(),
                $response->getReasonPhrase(),
                $response->getBody()
            )
        );
    }
}
