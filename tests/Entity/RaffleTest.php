<?php

namespace App\Tests\Entity;

use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\Entity\Raffle;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\Raffle
 * @group  todo
 */
class RaffleTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @expectedException \App\Exception\NoEventsToRaffleException
     */
    public function testCannotCreateEmptyRaffle(): void
    {
        Raffle::create([]);
    }

    /**
     * @expectedException \App\Exception\NoCommentsToRaffleException
     */
    public function testCannotStartRaffleWithNoCommentsInEvents(): void
    {
        // Arrange.
        $talk1 = Mockery::mock(JoindInTalk::class);
        $talk1->shouldReceive('getCommentCount')->andReturn(0);

        $event1 = Mockery::mock(JoindInEvent::class);
        $event1->shouldReceive('getTalks')->andReturn(new ArrayCollection([$talk1]));

        $events = new ArrayCollection([$event1]);

        // Act.

        new Raffle('id', $events);
    }

    /**
     * @expectedException \App\Exception\NoCommentsToRaffleException
     */
    public function testCannotStartRaffleWhenThereAreNoTalksOnSelectedEvents(): void
    {
        // Arrange.
        $event1 = new JoindInEvent(1, 'Meetup 1', new \DateTime());
        $event2 = new JoindInEvent(2, 'Meetup 2', new \DateTime());
        $event3 = new JoindInEvent(3, 'Meetup 3', new \DateTime());

        $events = new ArrayCollection([$event1, $event2, $event3]);

        // Act.

        new Raffle('id', $events);
    }

    public function testRaffleWillBeCreated(): void
    {
        // Arrange.

        $talk1 = Mockery::mock(JoindInTalk::class);
        $talk1->shouldReceive('getCommentCount')->andReturn(1);

        $event1 = new JoindInEvent(1, 'Meetup #1', new \DateTime());
        $event1->addTalk($talk1);

        $events = new ArrayCollection([$event1]);

        // Act.

        $raffle = new Raffle('id', $events);

        // Assert.

        $this->assertInstanceOf(Raffle::class, $raffle);
    }

    public function testRaffleWillBeCreated2(): void
    {
        // Arrange.

        $talk1 = Mockery::mock(JoindInTalk::class);
        $talk1->shouldReceive('getCommentCount')->andReturn(1);

        $event1 = Mockery::mock(JoindInEvent::class);
        $event1->shouldReceive('getTalks')->andReturn(new ArrayCollection([$talk1]));

        $events = new ArrayCollection([$event1]);

        // Act.

        $raffle = new Raffle('id', $events);

        // Assert.

        $this->assertInstanceOf(Raffle::class, $raffle);
    }
}
