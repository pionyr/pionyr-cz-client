<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use PascalDeVink\ShortUuid\ShortUuid;
use Pionyr\PionyrCz\Http\RequestManager;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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
        return new ArticleRequestBuilder($this->requestManager, $this->getUuidFromString($uuidOrShortUuid));
    }

    public function events(): EventsRequestBuilder
    {
        return new EventsRequestBuilder($this->requestManager);
    }

    public function event(string $uuidOrShortUuid): EventRequestBuilder
    {
        return new EventRequestBuilder($this->requestManager, $this->getUuidFromString($uuidOrShortUuid));
    }

    public function groups(): GroupsRequestBuilder
    {
        return new GroupsRequestBuilder($this->requestManager);
    }

    protected function getUuidFromString($uuidOrShortUuid): UuidInterface
    {
        if (mb_strlen($uuidOrShortUuid) === 36) {
            $uuidString = $uuidOrShortUuid;
        } else {
            $shortUuid = new ShortUuid();
            $uuidString = $shortUuid->decode($uuidOrShortUuid)->toString();
        }

        return Uuid::fromString($uuidString);
    }
}
