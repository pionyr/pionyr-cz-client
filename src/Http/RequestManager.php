<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http;

use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication\QueryParam;
use Http\Message\MessageFactory;
use Pionyr\PionyrCz\Http\Plugin\ExceptionPlugin;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Encapsulates HTTP layer, ie. request/response handling.
 * This class should not be typically used directly.
 */
class RequestManager
{
    /** @var string */
    private $baseUrl = 'https://pionyr.cz/api';
    /** @var string */
    protected $apiToken;
    /** @var HttpClient */
    protected $httpClient;
    /** @var MessageFactory */
    protected $messageFactory;

    public function __construct(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    public function sendRequest(string $method, string $path, array $queryParams = []): ResponseInterface
    {
        $httpRequest = $this->createHttpRequest($method, $path, $queryParams);

        $client = $this->createConfiguredHttpClient();

        // TODO: handle exceptions (or create custom plugin?)
        return $client->sendRequest($httpRequest);
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /** @codeCoverageIgnore */
    public function setHttpClient(HttpClient $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /** @codeCoverageIgnore */
    public function setMessageFactory(MessageFactory $messageFactory): void
    {
        $this->messageFactory = $messageFactory;
    }

    protected function getHttpClient(): HttpClient
    {
        if ($this->httpClient === null) {
            // @codeCoverageIgnoreStart
            $this->httpClient = HttpClientDiscovery::find();
            // @codeCoverageIgnoreEnd
        }

        return $this->httpClient;
    }

    protected function getMessageFactory(): MessageFactory
    {
        if ($this->messageFactory === null) {
            $this->messageFactory = MessageFactoryDiscovery::find();
        }

        return $this->messageFactory;
    }

    protected function createConfiguredHttpClient(): HttpClient
    {
        return new PluginClient(
            $this->getHttpClient(),
            [
                new RedirectPlugin(),
                new HeaderSetPlugin($this->getDefaultHeaders()),
                new AuthenticationPlugin(new QueryParam(['token' => $this->apiToken])),
                new ExceptionPlugin(),
            ]
        );
    }

    protected function createHttpRequest(string $method, string $path, array $queryParams = []): RequestInterface
    {
        $uri = $this->baseUrl . $path;

        $queryString = http_build_query($queryParams, '', '&');
        if (!empty($queryString)) {
            $uri .= '?' . $queryString;
        }

        return $this->getMessageFactory()
            ->createRequest($method, $uri);
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }
}
