<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder\Fixtures;

use Pionyr\PionyrCz\Http\Response\ResponseInterface;
use Pionyr\PionyrCz\RequestBuilder\AbstractRequestBuilder;

class DummyBuilder extends AbstractRequestBuilder
{
    protected function getPath(): string
    {
        return '/';
    }

    protected function processResponse(\stdClass $responseData): ResponseInterface
    {
        return new class() implements ResponseInterface {
            public function getData()
            {
                return [];
            }
        };
    }
}
