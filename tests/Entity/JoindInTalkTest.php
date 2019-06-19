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

    public function setUp(): void
    {
        $this->id          = 1;
        $this->title       = 'title';
        $this->event       = Mockery::mock(JoindInEvent::class);
        $this->joindInTalk = new JoindInTalk($this->id, $this->title, $this->event);
    }

    public function testSetTitle(): void
    {
        $this->markTestSkipped('Skipping');
    }

    public function testSetEvent(): void
    {
        $this->markTestSkipped('Skipping');
    }

    public function testGetId(): void
    {
        self::assertEquals($this->id, $this->joindInTalk->getId());
    }

    public function testGetTitle(): void
    {
        self::assertEquals($this->title, $this->joindInTalk->getTitle());
    }

    public function testGetEvent(): void
    {
        self::assertEquals($this->event, $this->joindInTalk->getEvent());
    }

    public function testGetCreatedAt(): void
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

    public function testJsonSerialize(): void
    {
        $this->markTestSkipped('Skipping');
    }
}
