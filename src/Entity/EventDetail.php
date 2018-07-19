<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

class EventDetail extends AbstractEvent
{
    /** @var Photo[] */
    protected $photos = [];
    /** @var File[] */
    protected $files = [];
    /** @var Link[] */
    protected $links = [];
    /** @var int|null */
    protected $ageFrom;
    /** @var int|null */
    protected $ageTo;
    /** @var int|null */
    protected $expectedNumberOfParticipants;
    /** @var string|null */
    protected $transportation;
    /** @var string|null */
    protected $accommodation;
    /** @var string|null */
    protected $food;
    /** @var string */
    protected $requiredEquipment;

    public static function createFromResponseData(\stdClass $responseData): self
    {
        $object = new static();

        static::setCommonEventResponseDataToObject($responseData, $object);

        $object->ageFrom = $responseData->vekOd !== null ? (int) $responseData->vekOd : null;
        $object->ageTo = $responseData->vekDo !== null ? (int) $responseData->vekDo : null;
        $object->expectedNumberOfParticipants = $responseData->predpokladanyPocetUcastniku !== null
            ? (int) $responseData->predpokladanyPocetUcastniku : null;
        $object->transportation = $responseData->doprava;
        $object->accommodation = $responseData->ubytovani;
        $object->food = $responseData->strava;
        $object->requiredEquipment = $responseData->pozadovaneVybaveni;

        $object->setPhotos($responseData->fotografie);
        $object->setFiles($responseData->soubory);
        $object->setLinks($responseData->odkazy);

        return $object;
    }

    public function getAgeFrom(): ?int
    {
        return $this->ageFrom;
    }

    public function getAgeTo(): ?int
    {
        return $this->ageTo;
    }

    public function getExpectedNumberOfParticipants(): ?int
    {
        return $this->expectedNumberOfParticipants;
    }

    public function getTransportation(): ?string
    {
        return $this->transportation;
    }

    public function getAccommodation(): ?string
    {
        return $this->accommodation;
    }

    public function getFood(): ?string
    {
        return $this->food;
    }

    public function getRequiredEquipment(): string
    {
        return $this->requiredEquipment;
    }

    /**
     * @return Photo[]
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    private function setPhotos(array $photos): void
    {
        foreach ($photos as $photo) {
            $this->photos[] = Photo::create($photo->url, $photo->popisek);
        }
    }

    private function setFiles(array $files): void
    {
        foreach ($files as $file) {
            $this->files[] = File::create($file->url, $file->nazev, $file->jeVerejne);
        }
    }

    private function setLinks(array $links): void
    {
        foreach ($links as $link) {
            $this->links[] = Link::create($link->url, $link->titulek);
        }
    }
}
