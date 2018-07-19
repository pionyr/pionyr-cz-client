<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http\Response;

use Assert\Assertion;
use Pionyr\PionyrCz\Entity\ArticlePreview;

class ArticlesResponse extends AbstractListResponse
{
    /** @var ArticlePreview[] */
    private $data;

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

    protected function setData(array $data): void
    {
        Assertion::allIsInstanceOf($data, ArticlePreview::class);

        $this->data = $data;
    }
}
