<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Pionyr\PionyrCz\Http\RequestManager;

class RequestBuilderFactory
{
    /** @var RequestManager */
    private $requestManager;

    public function __construct(RequestManager $requestManager)
    {
        $this->requestManager = $requestManager;
    }

    public function articles(): ArticlesRequestBuilder
    {
        return new ArticlesRequestBuilder($this->requestManager);
    }
}
