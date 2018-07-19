<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

use PascalDeVink\ShortUuid\ShortUuid;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Trait to add UUID & ShortUUID capabilities to value objects.
 */
trait IdentifiableTrait
{
    /** @var UuidInterface */
    protected $uuid;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getShortUuid(): string
    {
        $shortUuid = new ShortUuid();

        return $shortUuid->encode($this->getUuid());
    }

    protected function setUuidFromString(string $uuid): void
    {
        $this->uuid = Uuid::fromString($uuid);
    }
}
