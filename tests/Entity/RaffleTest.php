<?php

namespace App\Tests\Entity;

use App\Entity\Raffle;
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
    /** @var Raffle */
    private $raffle;

    public function setUp()
    {
        $this->id     = 'id';
        $this->events = Mockery::mock(Collection::class);
        $this->raffle = new Raffle($this->id, $this->events);
    }

    public function testCreate()
    {
        $this->markTestSkipped('Skipping');
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
