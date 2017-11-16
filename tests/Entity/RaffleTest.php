<?php

namespace App\Tests\Entity;

use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\Entity\Raffle;
use App\Exception\NoCommentsToRaffleException;
use App\Exception\NoEventsToRaffleException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\Raffle
 * @group todo
 */
class RaffleTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var string */
    private $id;
    /** @var MockInterface|Collection */
    private $events;
    /** @var MockInterface|\App\Entity\JoindInTalk */
    private $talk;
    /** @var Raffle */
    private $raffle;
    /** @var MockInterface|JoindInEvent  */
    private $event1;

    public function setUp()
    {
        $this->id     = 'id';
        $this->event1 = Mockery::mock(JoindInEvent::class);
        $this->events = new ArrayCollection([$this->event1]);
        $this->raffle = new Raffle($this->id, $this->events);
    }

    public function testCreate()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testCannotCreateEmptyRaffle()
    {
        $this->expectException(NoEventsToRaffleException::class);
        Raffle::create([]);
    }

    public function testGetId()
    {
        self::assertEquals($this->id, $this->raffle->getId());
    }

    public function testGetEvents()
    {
        self::assertEquals($this->events, $this->raffle->getEvents());
    }

    public function testGetCreatedAt()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testGetCommentsEligibleForRaffling()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testCannotStartARaffleWithNoCommentsInEvents()
    {
        $this->expectException(NoCommentsToRaffleException::class);

        $talk = new JoindInTalk(1,'bla bla' , $this->event1);

        $this->event1->shouldReceive('getTalks')
            ->andReturn(new ArrayCollection( [$talk]));

        $this->raffle->pick();
    }

    public function testPick()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testUserWon()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testUserIsNoShow()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testJsonSerialize()
    {
        $this->markTestSkipped('Skipping');
    }
}
