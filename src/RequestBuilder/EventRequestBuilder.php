<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Pionyr\PionyrCz\Entity\EventDetail;
use Pionyr\PionyrCz\Http\RequestManager;
use Pionyr\PionyrCz\Http\Response\EventResponse;
use Pionyr\PionyrCz\Http\Response\ResponseInterface;
use Ramsey\Uuid\UuidInterface;

/** @method EventResponse send() */
class EventRequestBuilder extends AbstractRequestBuilder
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
        return '/akceDetail/';
    }

    protected function getQueryParams(): array
    {
        return [
            'guid' => $this->uuid->toString(),
        ];
    }

    protected function processResponse(\stdClass $responseData): ResponseInterface
    {
        $event = EventDetail::createFromResponseData($responseData);

        return EventResponse::create($event);
    }
}
