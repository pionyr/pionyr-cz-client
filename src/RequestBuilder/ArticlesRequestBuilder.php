<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

class ArticlesRequestBuilder extends AbstractRequestBuilder
{
    protected function getPath(): string
    {
        return '/clanky/';
    }
}
