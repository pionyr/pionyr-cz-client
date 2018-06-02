<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

use Pionyr\PionyrCz\Constants\Region;
use Pionyr\PionyrCz\Helper\DateTimeFactory;

class ArticleDetail extends AbstractArticle
{
    /** @var string */
    protected $text;
    /** @var string|null */
    protected $textPhotoUrl;
    /** @var \DateTimeImmutable|null */
    protected $dateShowFrom;
    /** @var \DateTimeImmutable|null */
    protected $dateShowTo;
    /** @var bool */
    protected $isNews;
    /** @var bool */
    protected $isNewsForMembersPublic;
    /** @var bool */
    protected $isNewsForMembersPrivate;
    /** @var bool */
    protected $isMyRegion;
    /** @var bool */
    protected $isMozaika;
    /** @var bool */
    protected $isOfferedToOtherRegions;
    /** @var Region[] */
    protected $regions = [];
    /** @var Photo[] */
    protected $photos = [];
    /** @var Link[] */
    protected $links = [];

    public static function createFromResponseData(\stdClass $responseData): self
    {
        $object = new static();

        static::setCommonArticleResponseDataToObject($responseData, $object);

        $object->text = $responseData->text;
        $object->textPhotoUrl = $responseData->textUrl;
        $object->dateShowFrom = DateTimeFactory::fromNullableInputString($responseData->datumZobrazitOd);
        $object->dateShowTo = DateTimeFactory::fromNullableInputString($responseData->datumZobrazitDo);
        $object->isNews = $responseData->jeAktualita;
        $object->isNewsForMembersPublic = $responseData->jeProClenyVerejna;
        $object->isNewsForMembersPrivate = $responseData->jeProClenyNeverejna;
        $object->isMyRegion = $responseData->jeMujKrajskyWeb;
        $object->isMozaika = $responseData->jeMozaika;
        $object->isOfferedToOtherRegions = $responseData->jeNabidnutDalsim;
        $object->setRegions($responseData);
        $object->setPhotos($responseData->fotografie);
        $object->setLinks($responseData->odkazy);

        return $object;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getTextPhotoUrl(): ?string
    {
        return $this->textPhotoUrl;
    }

    public function getDateShowFrom(): ?\DateTimeImmutable
    {
        return $this->dateShowFrom;
    }

    public function getDateShowTo(): ?\DateTimeImmutable
    {
        return $this->dateShowTo;
    }

    public function isNews(): bool
    {
        return $this->isNews;
    }

    public function isNewsForMembersPublic(): bool
    {
        return $this->isNewsForMembersPublic;
    }

    public function isNewsForMembersPrivate(): bool
    {
        return $this->isNewsForMembersPrivate;
    }

    public function isMyRegion(): bool
    {
        return $this->isMyRegion;
    }

    public function isMozaika(): bool
    {
        return $this->isMozaika;
    }

    public function isOfferedToOtherRegions(): bool
    {
        return $this->isOfferedToOtherRegions;
    }

    /**
     * @return Region[]
     */
    public function getRegions(): array
    {
        return $this->regions;
    }

    /**
     * @return Photo[]
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    private function setRegions(\stdClass $responseData): void
    {
        $availableRegions = Region::toArray();
        foreach ($availableRegions as $regionShortcut) {
            if (!empty($responseData->{'je' . $regionShortcut})) {
                $this->regions[$regionShortcut] = Region::$regionShortcut();
            }
        }
    }

    private function setPhotos(array $photos): void
    {
        foreach ($photos as $photo) {
            $this->photos[] = Photo::create($photo->url, $photo->popisek);
        }
    }

    private function setLinks(array $links): void
    {
        foreach ($links as $link) {
            $this->links[] = Link::create($link->url, $link->titulek);
        }
    }
}
