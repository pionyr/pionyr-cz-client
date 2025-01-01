<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpException extends \RuntimeException implements PionyrCzExceptionInterface
{
    /** @var ResponseInterface */
    protected $response;
    /** @var RequestInterface */
    protected $request;

    public function __construct(
        string $message,
        RequestInterface $request,
        ResponseInterface $response,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);

        $this->request = $request;
        $this->response = $response;
        $this->code = $response->getStatusCode();
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
