<?php

namespace App\Tests\JoindIn;

use App\JoindIn\EventDataFactory;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\JoindIn\EventDataFactory
 * @group todo
 */
class EventDataFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var EventDataFactory */
    private $eventDataFactory;

    public function setUp(): void
    {
        $this->eventDataFactory = new EventDataFactory();
    }

    public function testCreate(): void
    {
        $this->markTestSkipped('Skipping');
    }
}
