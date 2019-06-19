<?php

namespace App\Tests\Entity;

use App\Entity\JoindInUser;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\JoindInUser
 * @group todo
 */
class JoindInUserTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var int */
    private $id;
    /** @var string */
    private $username;
    /** @var string */
    private $displayName;
    /** @var JoindInUser */
    private $joindInUser;
    /** @var bool */
    private $organizer;

    public function setUp(): void
    {
        $this->id          = 1;
        $this->username    = 'username';
        $this->displayName = 'displayName';
        $this->organizer   = false;
        $this->joindInUser = new JoindInUser($this->id, $this->username, $this->displayName, $this->organizer);
    }

    public function testSetUsername(): void
    {
        $this->markTestSkipped('Skipping');
    }

    public function testSetDisplayName(): void
    {
        $this->markTestSkipped('Skipping');
    }

    public function testPromoteToOrganizer(): void
    {
        $this->joindInUser->promoteToOrganizer();
        self::assertEquals(true, $this->joindInUser->isOrganizer());
    }

    public function testIsOrganizer(): void
    {
        self::assertEquals($this->organizer, $this->joindInUser->isOrganizer());
    }

    public function testGetId(): void
    {
        self::assertEquals($this->id, $this->joindInUser->getId());
    }

    public function testGetUsername(): void
    {
        self::assertEquals($this->username, $this->joindInUser->getUsername());
    }

    public function testGetDisplayName(): void
    {
        self::assertEquals($this->displayName, $this->joindInUser->getDisplayName());
    }

    public function testJsonSerialize(): void
    {
        $this->markTestSkipped('Skipping');
    }

    public function testGetComments(): void
    {
        $this->markTestSkipped('Skipping');
    }

    public function testAddComment(): void
    {
        $this->markTestSkipped('Skipping');
    }
}
