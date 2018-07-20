<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http\Response;

use Assert\Assertion;
use Pionyr\PionyrCz\Entity\EventPreview;

class EventsResponse extends AbstractListResponse
{
    /** @var EventPreview[] */
    private $data;

    public static function create(array $data, int $pageCount, int $itemTotalCount): self
    {
        return new static($data, $pageCount, $itemTotalCount);
    }

    /**
     * @return EventPreview[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    protected function setData(array $data): void
    {
        Assertion::allIsInstanceOf($data, EventPreview::class);

        $this->data = $data;
    }
}
