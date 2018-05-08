<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Pionyr\PionyrCz\Http\RequestManager;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractRequestBuilder
{
    /** @var RequestManager */
    private $requestManager;

    public function __construct(RequestManager $requestManager)
    {
        $this->requestManager = $requestManager;
    }

    abstract protected function getPath(): string;

    public function send(): ResponseInterface
    {
        // TODO: serialize to value object
        return $this->requestManager->sendRequest(
            RequestMethodInterface::METHOD_GET,
            $this->getPath()
        );
    }
}
