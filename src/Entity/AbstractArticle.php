<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

use Pionyr\PionyrCz\Constants\ArticleCategory;
use Pionyr\PionyrCz\Helper\DateTimeFactory;

class AbstractArticle
{
    use IdentifiableTrait;

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
        $object->setUuidFromString($responseData->guid);
        $object->title = $responseData->nazev;
        $object->datePublished = DateTimeFactory::fromInputString($responseData->datumPublikovani);
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
