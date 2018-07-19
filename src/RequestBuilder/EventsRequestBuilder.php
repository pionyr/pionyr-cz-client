<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\RequestBuilder;

use Pionyr\PionyrCz\Constants\EventCategory;
use Pionyr\PionyrCz\Entity\EventPreview;
use Pionyr\PionyrCz\Http\Response\EventsResponse;
use Pionyr\PionyrCz\Http\Response\ResponseInterface;

/** @method EventsResponse send() */
class EventsRequestBuilder extends AbstractRequestBuilder
{
    /** @var int|null */
    protected $page;
    /** @var EventCategory|null */
    protected $category;
    /** @var \DateTimeInterface|null */
    protected $dateFrom;
    /** @var \DateTimeInterface|null */
    protected $dateTo;

    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function setCategory(?EventCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function setDateFrom(?\DateTimeInterface $date): self
    {
        $this->dateFrom = $date;

        return $this;
    }

    public function setDateTo(?\DateTimeInterface $date): self
    {
        $this->dateTo = $date;

        return $this;
    }

    protected function getPath(): string
    {
        return '/akce/';
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

        if ($this->dateFrom !== null) {
            $params['datumOd'] = $this->dateFrom->format('Y-m-d');
        }

        if ($this->dateTo !== null) {
            $params['datumDo'] = $this->dateTo->format('Y-m-d');
        }

        return $params;
    }

    protected function processResponse(\stdClass $responseData): ResponseInterface
    {
        $events = EventPreview::createFromResponseDataArray((array) $responseData->seznam);

        return EventsResponse::create($events, $responseData->celkemStranek, $responseData->celkemPolozek);
    }
}
