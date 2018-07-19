<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Pionyr\PionyrCz\Entity\AbstractUnit
 * @covers \Pionyr\PionyrCz\Entity\Group
 */
class GroupTest extends TestCase
{
    /** @test */
    public function shouldCreateGroupFromResponseJson(): void
    {
        $responseData = json_decode(file_get_contents(__DIR__ . '/Fixtures/group.json'));

        $group = Group::createFromResponseData($responseData);

        $this->assertSame('33. pionýrská skupina Trojka', $group->getName());
        $this->assertSame('trojita@test.test', $group->getEmail());
        $this->assertSame('https://www.tritritri.trms', $group->getWebsiteUrl());
        $this->assertSame('333666333', $group->getPhone());
    }

    /** @test */
    public function shouldCreateMultipleEntitiesFromResponseJson(): void
    {
        $responseData = json_decode(file_get_contents(__DIR__ . '/Fixtures/group-list.json'));

        $groups = Group::createFromResponseDataArray((array) $responseData);

        $this->assertCount(3, $groups);
        $this->assertContainsOnlyInstancesOf(Group::class, $groups);
    }
}
