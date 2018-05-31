<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use PascalDeVink\ShortUuid\ShortUuid;
use Pionyr\PionyrCz\Http\RequestManager;
use Ramsey\Uuid\Uuid;

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

    public function article(string $uuidOrShortUuid): ArticleRequestBuilder
    {
        if (mb_strlen($uuidOrShortUuid) === 36) {
            $uuidString = $uuidOrShortUuid;
        } else {
            $shortUuid = new ShortUuid();
            $uuidString = $shortUuid->decode($uuidOrShortUuid)->toString();
        }

        $uuid = Uuid::fromString($uuidString);

        return new ArticleRequestBuilder($this->requestManager, $uuid);
    }
}
