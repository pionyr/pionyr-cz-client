<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

use Pionyr\PionyrCz\Constants\EventCategory;
use Pionyr\PionyrCz\Constants\EventLocalization;
use Pionyr\PionyrCz\Helper\DateTimeFactory;

class AbstractEvent
{
    use IdentifiableTrait;

    protected const LOCALIZATION_NATIONWIDE = 'Celorepubliková';
    /** @var string */
    protected $title;
    /** @var string */
    protected $description;
    /** @var EventCategory|null */
    protected $category;
    /** @var string|null */
    protected $photoUrl;
    /** @var string */
    protected $organizer;
    /** @var \DateTimeImmutable */
    protected $dateFrom;
    /** @var \DateTimeImmutable */
    protected $dateTo;
    /** @var bool */
    protected $isImportant;
    /** @var string */
    protected $place;
    /** @var string */
    protected $region;
    /** @var string */
    protected $websiteUrl;
    /** @var string|null */
    protected $priceForMembers;
    /** @var string|null */
    protected $priceForMembersDiscounted;
    /** @var string|null */
    protected $priceForPublic;
    /** @var string|null */
    protected $priceForPublicDiscounted;
    /** @var \DateTimeImmutable|null */
    protected $datePublishFrom;
    /** @var \DateTimeImmutable|null */
    protected $datePublishTo;
    /** @var EventLocalization|null */
    protected $localization;
    /** @var bool */
    protected $isShownInCalendar;
    /** @var bool */
    protected $isOpenEvent;
    /** @var string|null */
    protected $openEventType;
    /** @var bool */
    protected $isForKids;
    /** @var bool */
    protected $isForLeaders;
    /** @var bool */
    protected $isForPublic;

    protected function __construct()
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): ?EventCategory
    {
        return $this->category;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function getOrganizer(): string
    {
        return $this->organizer;
    }

    public function getDateFrom(): \DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTo(): \DateTimeImmutable
    {
        return $this->dateTo;
    }

    public function isImportant(): bool
    {
        return $this->isImportant;
    }

    public function getPlace(): string
    {
        return $this->place;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function getWebsiteUrl(): string
    {
        return $this->websiteUrl;
    }

    public function getPriceForMembers(): ?string
    {
        return $this->priceForMembers;
    }

    public function getPriceForMembersDiscounted(): ?string
    {
        return $this->priceForMembersDiscounted;
    }

    public function getPriceForPublic(): ?string
    {
        return $this->priceForPublic;
    }

    public function getPriceForPublicDiscounted(): ?string
    {
        return $this->priceForPublicDiscounted;
    }

    public function getLocalization(): ?EventLocalization
    {
        return $this->localization;
    }

    public function getDatePublishFrom(): ?\DateTimeImmutable
    {
        return $this->datePublishFrom;
    }

    public function getDatePublishTo(): ?\DateTimeImmutable
    {
        return $this->datePublishTo;
    }

    public function isShownInCalendar(): bool
    {
        return $this->isShownInCalendar;
    }

    public function isOpenEvent(): bool
    {
        return $this->isOpenEvent;
    }

    public function getOpenEventType(): ?string
    {
        return $this->openEventType;
    }

    public function isForKids(): bool
    {
        return $this->isForKids;
    }

    public function isForLeaders(): bool
    {
        return $this->isForLeaders;
    }

    public function isForPublic(): bool
    {
        return $this->isForPublic;
    }

    protected static function setCommonEventResponseDataToObject(\stdClass $responseData, self $object): void
    {
        $object->setUuidFromString($responseData->guid);
        $object->title = $responseData->nazev;
        $object->description = $responseData->popis;
        $object->setCategory($responseData->typAkceId);
        $object->photoUrl = $responseData->logoUrl;
        $object->organizer = $responseData->poradatel;
        $object->dateFrom = DateTimeFactory::fromInputString($responseData->terminOd . ' ' . $responseData->casOd);
        $object->dateTo = DateTimeFactory::fromInputString($responseData->terminDo . ' ' . $responseData->casDo);
        $object->isImportant = $responseData->jeDulezityTermin;
        $object->place = $responseData->mistoKonani;
        $object->region = $responseData->kraj;
        $object->websiteUrl = $responseData->web ?: '';
        $object->priceForMembers = $responseData->cenaStandardniCleni ?: null;
        $object->priceForMembersDiscounted = $responseData->cenaZvyhodnenaCleni ?: null;
        $object->priceForPublic = $responseData->cenaStandardniVerejnost ?: null;
        $object->priceForPublicDiscounted = $responseData->cenaZvyhodnenaVerejnost ?: null;
        $object->datePublishFrom = DateTimeFactory::fromNullableInputString($responseData->zverejnitOd);
        $object->datePublishTo = DateTimeFactory::fromNullableInputString($responseData->zverejnitDo);
        $object->setLocalizationFromString($responseData->lokalizace);
        $object->isShownInCalendar = $responseData->jeZobrazitVKalendariu;
        $object->isOpenEvent = $responseData->jeOtevrenaAkce;
        $object->openEventType = $responseData->typOtevreneAkce ?: null;
        $object->isForKids = $responseData->jeProDeti;
        $object->isForLeaders = $responseData->jeProVedouci;
        $object->isForPublic = $responseData->jeProVerejnost;
    }

    protected function setCategory(int $categoryId): void
    {
        if (EventCategory::isValid($categoryId)) {
            $this->category = new EventCategory($categoryId);
        }
    }

    protected function setLocalizationFromString(?string $localization): void
    {
        if ($localization === 'Regionální') {
            $this->localization = new EventLocalization(EventLocalization::REGIONAL);
        } elseif ($localization === 'Celorepubliková') {
            $this->localization = new EventLocalization(EventLocalization::NATIONWIDE);
        }
    }
}
