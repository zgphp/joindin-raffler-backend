<?php

namespace App\Tests\JoindIn;

use App\JoindIn\UserData;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\JoindIn\UserData
 * @group  unit
 */
class UserDataTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var int */
    private $id;
    /** @var string */
    private $username;
    /** @var string */
    private $displayName;
    /** @var UserData */
    private $userData;

    public function setUp()
    {
        $this->id          = 1;
        $this->username    = 'username';
        $this->displayName = 'displayName';
        $this->userData    = new UserData($this->id, $this->username, $this->displayName);
    }

    public function testGetId()
    {
        self::assertEquals($this->id, $this->userData->getId());
    }

    public function testGetUsername()
    {
        self::assertEquals($this->username, $this->userData->getUsername());
    }

    public function testGetDisplayName()
    {
        self::assertEquals($this->displayName, $this->userData->getDisplayName());
    }
}
