<?php declare(strict_types=1);

namespace Pionyr\PionyrCz;

use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\RequestBuilder\RequestBuilderFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class PionyrCz
{
    /** @var string */
    private $apiToken;
    /** @var RequestManager */
    private $requestManager;

    public function __construct(string $apiToken)
    {
        $this->apiToken = $apiToken;
        $this->requestManager = new RequestManager($apiToken);
    }

    public function request(): RequestBuilderFactory
    {
        return new RequestBuilderFactory($this->getRequestManager());
    }

    /** @return $this */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->getRequestManager()->setBaseUrl($baseUrl);

        return $this;
    }

    /** @return $this */
    public function setHttpClient(ClientInterface $client): self
    {
        $this->getRequestManager()->setHttpClient($client);

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return $this
     */
    public function setHttpRequestFactory(RequestFactoryInterface $requestFactory): self
    {
        $this->getRequestManager()->setRequestFactory($requestFactory);

        return $this;
    }

    protected function getRequestManager(): RequestManager
    {
        return $this->requestManager;
    }
}
