<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http\Response;

use Assert\Assertion;
use Pionyr\PionyrCz\Entity\ArticlePreview;

class ArticlesResponse implements ResponseInterface
{
    /** @var ArticlePreview[] */
    private $data;
    /** @var int */
    private $pageCount;
    /** @var int */
    private $itemTotalCount;

    private function __construct(array $data, int $pageCount, int $itemTotalCount)
    {
        $this->setData($data);
        $this->pageCount = $pageCount;
        $this->itemTotalCount = $itemTotalCount;
    }

    public static function create(array $data, int $pageCount, int $itemTotalCount): self
    {
        return new static($data, $pageCount, $itemTotalCount);
    }

    /**
     * @return ArticlePreview[]
     */
    public function getData(): array
    {
        return $this->data;
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

    protected function setData(array $data): void
    {
        Assertion::allIsInstanceOf($data, ArticlePreview::class);

        $this->data = $data;
    }
}