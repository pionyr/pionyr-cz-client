<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Pionyr\PionyrCz\Entity\ArticleDetail;
use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\Http\Response\ArticleResponse;
use Pionyr\PionyrCz\Http\Response\ResponseInterface;
use Ramsey\Uuid\UuidInterface;

/** @method ArticleResponse send() */
class ArticleRequestBuilder extends AbstractRequestBuilder
{
    /** @var UuidInterface */
    protected $uuid;

    public function __construct(RequestManager $requestManager, UuidInterface $uuid)
    {
        parent::__construct($requestManager);

        $this->uuid = $uuid;
    }

    protected function getPath(): string
    {
        return '/clanekDetail/';
    }

    protected function getQueryParams(): array
    {
        return [
            'guid' => $this->uuid->toString(),
        ];
    }

    protected function processResponse(\Psr\Http\Message\ResponseInterface $httpResponse): ResponseInterface
    {
        $responseData = json_decode($httpResponse->getBody()->getContents());

        /*if (json_last_error() !== JSON_ERROR_NONE) {
            // TODO
            //throw ResponseDecodingException::forJsonError(json_last_error_msg(), $httpResponse);}
        }*/

        $article = ArticleDetail::createFromResponseData($responseData);

        return ArticleResponse::create($article);
    }
}
