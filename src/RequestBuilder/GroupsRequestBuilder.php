<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Pionyr\PionyrCz\Entity\Group;
use Pionyr\PionyrCz\Http\Response\GroupsResponse;
use Pionyr\PionyrCz\Http\Response\ResponseInterface;

/** @method GroupsResponse send() */
class GroupsRequestBuilder extends AbstractRequestBuilder
{
    /** @var int|null */
    protected $page;

    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    protected function getPath(): string
    {
        return '/ps/';
    }

    protected function getQueryParams(): array
    {
        $params = [];

        if ($this->page !== null) {
            $params['stranka'] = $this->page;
        }

        return $params;
    }

    protected function processResponse(\stdClass $responseData): ResponseInterface
    {
        $groups = Group::createFromResponseDataArray((array) $responseData->seznam);

        return GroupsResponse::create($groups, $responseData->celkemStranek, $responseData->celkemPolozek);
    }
}
