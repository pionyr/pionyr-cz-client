<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

class File
{
    /** @var string */
    private $url;
    /** @var string */
    private $title;
    /** @var bool */
    private $isPublic;

    protected function __construct(string $url, string $title, bool $isPublic)
    {
        $this->url = $url;
        $this->title = $title;
        $this->isPublic = $isPublic;
    }

    public static function create(string $url, string $title, bool $isPublic): self
    {
        return new static($url, $title, $isPublic);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }
}
