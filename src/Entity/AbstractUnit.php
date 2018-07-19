<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

class AbstractUnit
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $websiteUrl;
    /** @var string */
    protected $phone;
    /** @var string */
    protected $email;

    protected function __construct()
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWebsiteUrl(): string
    {
        return $this->websiteUrl;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    protected static function setCommonUnitResponseDataToObject(\stdClass $responseData, self $object): void
    {
        $object->name = $responseData->nazev;
        $object->websiteUrl = $responseData->web;
        $object->phone = $responseData->telefon;
        $object->email = $responseData->email;
    }
}
