<?php

namespace App\Tests\Entity;

use App\Entity\JoindInEvent;
use DateTime;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\JoindInEvent
 * @group todo
 */
class JoindInEventTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var MockInterface|DateTime */
    private $date;
    /** @var JoindInEvent */
    private $joindInEvent;

    public function setUp()
    {
        $this->id           = 1;
        $this->name         = 'name';
        $this->date         = Mockery::mock(DateTime::class);
        $this->joindInEvent = new JoindInEvent($this->id, $this->name, $this->date);
    }

    public function testSetName()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testSetDate()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testGetId()
    {
        self::assertEquals($this->id, $this->joindInEvent->getId());
    }

    public function testGetName()
    {
        self::assertEquals($this->name, $this->joindInEvent->getName());
    }

    public function testGetDate()
    {
        self::assertEquals($this->date, $this->joindInEvent->getDate());
    }

    public function testGetTalks()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testAddTalk()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testJsonSerialize()
    {
        $this->markTestSkipped('Skipping');
    }
}
