<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

use PascalDeVink\ShortUuid\ShortUuid;
use Pionyr\PionyrCz\Constants\ArticleCategory;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AbstractArticle
{
    /** @var UuidInterface */
    protected $uuid;
    /** @var string */
    protected $title;
    /** @var \DateTimeImmutable */
    protected $datePublished;
    /** @var ArticleCategory */
    protected $category;
    /** @var string */
    protected $authorName;
    /** @var string */
    protected $perex;
    /** @var string|null */
    protected $perexPhotoUrl;

    protected function __construct()
    {
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getShortUuid(): string
    {
        $shortUuid = new ShortUuid();

        return $shortUuid->encode($this->getUuid());
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDatePublished(): \DateTimeImmutable
    {
        return $this->datePublished;
    }

    public function getCategory(): ArticleCategory
    {
        return $this->category;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getPerex(): string
    {
        return $this->perex;
    }

    public function getPerexPhotoUrl(): ?string
    {
        return $this->perexPhotoUrl;
    }

    protected static function setCommonArticleResponseDataToObject(\stdClass $responseData, self $object): void
    {
        $object->uuid = Uuid::fromString($responseData->guid);
        $object->title = $responseData->nazev;
        $object->datePublished = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $responseData->datumPublikovani
        );
        $object->category = new ArticleCategory($responseData->kategorieId);
        $object->setAuthorName($responseData->autorJmeno, $responseData->autorPrijmeni);
        $object->perex = $responseData->perex;
        $object->perexPhotoUrl = $responseData->perexUrl;
    }

    protected function setAuthorName(string $firstName = '', string $secondName = ''): void
    {
        $this->authorName = trim($firstName . ' ' . $secondName);
    }
}
