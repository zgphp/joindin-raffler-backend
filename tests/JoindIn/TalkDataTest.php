<?php

namespace App\Tests\JoindIn;

use App\Entity\JoindInEvent;
use App\JoindIn\TalkData;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\JoindIn\TalkData
 * @group  unit
 */
class TalkDataTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var int */
    private $id;
    /** @var string */
    private $title;
    /** @var MockInterface|JoindInEvent */
    private $event;
    /** @var TalkData */
    private $talkData;

    public function setUp()
    {
        $this->id       = 1;
        $this->title    = 'title';
        $this->event    = Mockery::mock(JoindInEvent::class);
        $this->talkData = new TalkData($this->id, $this->title, $this->event);
    }

    public function testGetId()
    {
        self::assertEquals($this->id, $this->talkData->getId());
    }

    public function testGetTitle()
    {
        self::assertEquals($this->title, $this->talkData->getTitle());
    }

    public function testGetEvent()
    {
        self::assertEquals($this->event, $this->talkData->getEvent());
    }
}
