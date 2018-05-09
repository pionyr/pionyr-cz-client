<?php declare(strict_types=1);

namespace Pionyr\PionyrCz;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\RequestBuilder\RequestBuilderFactory;

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
    public function setHttpClient(HttpClient $client): self
    {
        $this->getRequestManager()->setHttpClient($client);

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return $this
     */
    public function setHttpMessageFactory(MessageFactory $messageFactory): self
    {
        $this->getRequestManager()->setMessageFactory($messageFactory);

        return $this;
    }

    protected function getRequestManager(): RequestManager
    {
        return $this->requestManager;
    }
}
