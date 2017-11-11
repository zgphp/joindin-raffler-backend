<?php

namespace App\Tests\JoindIn;

use App\JoindIn\TalkDataFactory;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\JoindIn\TalkDataFactory
 * @group todo
 */
class TalkDataFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var TalkDataFactory */
    private $talkDataFactory;

    public function setUp()
    {
        $this->talkDataFactory = new TalkDataFactory();
    }

    public function testCreate()
    {
        $this->markTestSkipped('Skipping');
    }
}
