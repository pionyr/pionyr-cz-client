<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Pionyr\PionyrCz\Constants\ArticleCategory;
use Pionyr\PionyrCz\Entity\ArticlePreview;
use Pionyr\PionyrCz\Http\Response\ArticlesResponse;
use Pionyr\PionyrCz\Http\Response\ResponseInterface;

/** @method ArticlesResponse send() */
class ArticlesRequestBuilder extends AbstractRequestBuilder
{
    /** @var int|null */
    protected $page;
    /** @var ArticleCategory|null */
    protected $category;

    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function setCategory(?ArticleCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    protected function getPath(): string
    {
        return '/clanky/';
    }

    protected function getQueryParams(): array
    {
        $params = [];

        if ($this->page !== null) {
            $params['stranka'] = $this->page;
        }

        if ($this->category !== null) {
            $params['kategorie'] = $this->category->getValue();
        }

        return $params;
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
