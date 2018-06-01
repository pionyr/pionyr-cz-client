<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

class Photo
{
    /** @var string */
    private $url;
    /** @var string */
    private $title;

    protected function __construct(string $url, string $title)
    {
        $this->url = $url;
        $this->title = $title;
    }

    public static function create(string $url, string $title): self
    {
        return new static($url, $title);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
