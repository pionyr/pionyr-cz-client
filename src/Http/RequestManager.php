<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http;

use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\Authentication\QueryParam;
use Pionyr\PionyrCz\Http\Plugin\ExceptionPlugin;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
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
    /** @var ClientInterface */
    protected $httpClient;
    /** @var RequestFactoryInterface */
    protected $requestFactory;

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
    public function setHttpClient(ClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /** @codeCoverageIgnore */
    public function setRequestFactory(RequestFactoryInterface $requestFactory): void
    {
        $this->requestFactory = $requestFactory;
    }

    protected function getHttpClient(): ClientInterface
    {
        if ($this->httpClient === null) {
            // @codeCoverageIgnoreStart
            $this->httpClient = Psr18ClientDiscovery::find();
            // @codeCoverageIgnoreEnd
        }

        return $this->httpClient;
    }

    protected function getRequestFactory(): RequestFactoryInterface
    {
        if ($this->requestFactory === null) {
            $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        }

        return $this->requestFactory;
    }

    protected function createConfiguredHttpClient(): ClientInterface
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

        return $this->getRequestFactory()
            ->createRequest($method, $uri);
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }
}
