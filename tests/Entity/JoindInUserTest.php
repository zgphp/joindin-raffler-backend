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

    public function setUp()
    {
        $this->id          = 1;
        $this->username    = 'username';
        $this->displayName = 'displayName';
        $this->organizer   = false;
        $this->joindInUser = new JoindInUser($this->id, $this->username, $this->displayName, $this->organizer);
    }

    public function testSetUsername()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testSetDisplayName()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testPromoteToOrganizer()
    {
        $this->joindInUser->promoteToOrganizer();
        self::assertEquals(true, $this->joindInUser->isOrganizer());
    }

    public function testIsOrganizer()
    {
        self::assertEquals($this->organizer, $this->joindInUser->isOrganizer());
    }

    public function testGetId()
    {
        self::assertEquals($this->id, $this->joindInUser->getId());
    }

    public function testGetUsername()
    {
        self::assertEquals($this->username, $this->joindInUser->getUsername());
    }

    public function testGetDisplayName()
    {
        self::assertEquals($this->displayName, $this->joindInUser->getDisplayName());
    }

    public function testJsonSerialize()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testGetComments()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testAddComment()
    {
        $this->markTestSkipped('Skipping');
    }
}
