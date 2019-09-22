<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

class Group extends AbstractUnit
{
    /** @var string */
    protected $ico;
    /** @var Address */
    protected $addressOfficial;
    /** @var Address */
    protected $addressWhereToFindUs;

    public static function createFromResponseData(\stdClass $responseData): self
    {
        $object = new static();

        static::setCommonUnitResponseDataToObject($responseData, $object);

        $object->setIco($responseData->ico);
        $object->setAddressOfficial($responseData->adresaSidla);
        $object->setAddressWhereToFindUs($responseData->adresaKdeNasNajdete);

        return $object;
    }

    public function getIco(): string
    {
        return $this->ico;
    }

    public function getAddressOfficial(): Address
    {
        return $this->addressOfficial;
    }

    public function getAddressWhereToFindUs(): Address
    {
        return $this->addressWhereToFindUs;
    }

    /**
     * @return Group[]
     */
    public static function createFromResponseDataArray(array $responseDataArray): array
    {
        $entities = [];
        foreach ($responseDataArray as $responseData) {
            $entities[] = self::createFromResponseData($responseData);
        }

        return $entities;
    }

    private function setIco(string $ico): void
    {
        $this->ico = $ico;
    }

    private function setAddressOfficial(\stdClass $address): void
    {
        $this->addressOfficial = Address::create($address->mesto, $address->ulice, $address->psc);
    }

    private function setAddressWhereToFindUs(\stdClass $address): void
    {
        $this->addressWhereToFindUs = Address::create($address->mesto, $address->ulice, $address->psc);
    }
}
