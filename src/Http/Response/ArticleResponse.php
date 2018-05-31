<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Http\Response;

use Pionyr\PionyrCz\Entity\ArticleDetail;

class ArticleResponse implements ResponseInterface
{
    /** @var ArticleDetail */
    private $data;

    private function __construct(ArticleDetail $data)
    {
        $this->data = $data;
    }

    public static function create(ArticleDetail $data): self
    {
        return new static($data);
    }

    public function getData(): ArticleDetail
    {
        return $this->data;
    }
}
