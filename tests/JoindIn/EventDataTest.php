<?php

namespace App\Tests\JoindIn;

use App\JoindIn\EventData;
use DateTime;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\JoindIn\EventData
 * @group  unit
 */
class EventDataTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var MockInterface|DateTime */
    private $date;
    /** @var EventData */
    private $eventData;

    public function setUp()
    {
        $this->id        = 1;
        $this->name      = 'name';
        $this->date      = Mockery::mock(DateTime::class);
        $this->eventData = new EventData($this->id, $this->name, $this->date);
    }

    public function testGetId()
    {
        self::assertEquals($this->id, $this->eventData->getId());
    }

    public function testGetName()
    {
        self::assertEquals($this->name, $this->eventData->getName());
    }

    public function testGetDate()
    {
        self::assertEquals($this->date, $this->eventData->getDate());
    }
}
