<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

class Address
{
    /** @var string */
    private $city;
    /** @var string */
    private $street;
    /** @var string */
    private $postcode;

    protected function __construct(string $city, string $street, string $postcode)
    {
        $this->city = $city;
        $this->street = $street;
        $this->postcode = $postcode;
    }

    public static function create(string $city, string $street, string $postcode): self
    {
        return new static($city, $street, $postcode);
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }
}
