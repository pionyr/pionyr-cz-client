<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Pionyr\PionyrCz\Entity\ArticlePreview;
use Pionyr\PionyrCz\Http\Response\ArticlesResponse;
use Pionyr\PionyrCz\Http\Response\ResponseInterface;

/** @method ArticlesResponse send() */
class ArticlesRequestBuilder extends AbstractRequestBuilder
{
    protected function getPath(): string
    {
        return '/clanky/';
    }

    protected function processResponse(\Psr\Http\Message\ResponseInterface $httpResponse): ResponseInterface
    {
        $responseData = json_decode($httpResponse->getBody()->getContents());

        /*if (json_last_error() !== JSON_ERROR_NONE) {
            // TODO
            //throw ResponseDecodingException::forJsonError(json_last_error_msg(), $httpResponse);}
        }*/

        $articles = ArticlePreview::createFromResponseDataArray((array) $responseData->seznam);

        return ArticlesResponse::create($articles, $responseData->celkemStranek, $responseData->celkemPolozek);
    }
}
