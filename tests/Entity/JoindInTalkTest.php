<?php

namespace App\Tests\Entity;

use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\JoindInTalk
 * @group todo
 */
class JoindInTalkTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var int */
    private $id;
    /** @var string */
    private $title;
    /** @var MockInterface|JoindInEvent */
    private $event;
    /** @var JoindInTalk */
    private $joindInTalk;

    public function setUp()
    {
        $this->id          = 1;
        $this->title       = 'title';
        $this->event       = Mockery::mock(JoindInEvent::class);
        $this->joindInTalk = new JoindInTalk($this->id, $this->title, $this->event);
    }

    public function testSetTitle()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testSetEvent()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testGetId()
    {
        self::assertEquals($this->id, $this->joindInTalk->getId());
    }

    public function testGetTitle()
    {
        self::assertEquals($this->title, $this->joindInTalk->getTitle());
    }

    public function testGetEvent()
    {
        self::assertEquals($this->event, $this->joindInTalk->getEvent());
    }

    public function testGetCreatedAt()
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

    public function testJsonSerialize()
    {
        $this->markTestSkipped('Skipping');
    }
}
