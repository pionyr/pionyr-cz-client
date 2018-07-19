<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http\Response;

abstract class AbstractListResponse implements ResponseInterface
{
    /** @var int */
    protected $pageCount;
    /** @var int */
    protected $itemTotalCount;

    protected function __construct(array $data, int $pageCount, int $itemTotalCount)
    {
        $this->setData($data);
        $this->pageCount = $pageCount;
        $this->itemTotalCount = $itemTotalCount;
    }

    /** @return int */
    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    /** @return int */
    public function getItemTotalCount(): int
    {
        return $this->itemTotalCount;
    }

    abstract protected function setData(array $data): void;
}
