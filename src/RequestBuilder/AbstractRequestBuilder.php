<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\Http\Response\ResponseInterface;

abstract class AbstractRequestBuilder
{
    /** @var RequestManager */
    private $requestManager;

    public function __construct(RequestManager $requestManager)
    {
        $this->requestManager = $requestManager;
    }

    public function send(): ResponseInterface
    {
        $response = $this->requestManager->sendRequest(
            RequestMethodInterface::METHOD_GET,
            $this->getPath()
        );

        return $this->processResponse($response);
    }

    abstract protected function getPath(): string;

    abstract protected function processResponse(\Psr\Http\Message\ResponseInterface $httpResponse): ResponseInterface;
}
