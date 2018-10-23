<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Pionyr\PionyrCz\Entity\ArticlePreview;
use Pionyr\PionyrCz\Http\Response\ArticlesResponse;
use Pionyr\PionyrCz\Http\Response\ResponseInterface;

/** @method ArticlesResponse send() */
class ArticlesRequestBuilder extends AbstractRequestBuilder
{
    /** @var int|null */
    protected $page;
    /** @var int */
    protected $categoryId;

    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function setCategory(int $categoryId): self
    {
        $this->categoryId = $categoryId;

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

        if ($this->categoryId !== null) {
            $params['kategorie'] = $this->categoryId;
        }

        return $params;
    }

    protected function processResponse(\stdClass $responseData): ResponseInterface
    {
        $articles = ArticlePreview::createFromResponseDataArray((array) $responseData->seznam);

        return ArticlesResponse::create($articles, $responseData->celkemStranek, $responseData->celkemPolozek);
    }
}
