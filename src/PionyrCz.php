<?php declare(strict_types=1);

namespace Pionyr\PionyrCz;

class PionyrCz
{
    private const API_BASE_URL_DEFAULT = 'https://pionyr.cz/api';

    /** @var string */
    protected $apiToken;
    /** @var string */
    protected $apiBaseUrl;

    public function __construct(string $apiToken, string $apiBaseUrl = self::API_BASE_URL_DEFAULT)
    {
        $this->apiToken = $apiToken;
        $this->apiBaseUrl = $apiBaseUrl;
    }
}
