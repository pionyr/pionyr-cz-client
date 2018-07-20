<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http\Response;

use Assert\Assertion;
use Pionyr\PionyrCz\Entity\Group;

class GroupsResponse extends AbstractListResponse
{
    /** @var Group[] */
    private $data;

    public static function create(array $data, int $pageCount, int $itemTotalCount): self
    {
        return new static($data, $pageCount, $itemTotalCount);
    }

    /**
     * @return Group[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    protected function setData(array $data): void
    {
        Assertion::allIsInstanceOf($data, Group::class);

        $this->data = $data;
    }
}
