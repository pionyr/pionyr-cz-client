<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http\Response;

use Pionyr\PionyrCz\Entity\EventDetail;

class EventResponse implements ResponseInterface
{
    /** @var EventDetail */
    private $data;

    private function __construct(EventDetail $data)
    {
        $this->data = $data;
    }

    public static function create(EventDetail $data): self
    {
        return new static($data);
    }

    public function getData(): EventDetail
    {
        return $this->data;
    }
}
