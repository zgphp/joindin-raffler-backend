<?php

namespace App\Tests\Service;

use App\JoindIn\CommentDataFactory;
use App\JoindIn\EventDataFactory;
use App\JoindIn\TalkDataFactory;
use App\Service\JoindInClient;
use GuzzleHttp\Client;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\JoindInClient
 * @group todo
 */
class JoindInClientTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var MockInterface|Client */
    private $client;
    /** @var MockInterface|EventDataFactory */
    private $eventDataFactory;
    /** @var MockInterface|TalkDataFactory */
    private $talkDataFactory;
    /** @var MockInterface|CommentDataFactory */
    private $commentDataFactory;
    /** @var JoindInClient */
    private $joindInClient;

    public function setUp()
    {
        $this->client             = Mockery::mock(Client::class);
        $this->eventDataFactory   = Mockery::mock(EventDataFactory::class);
        $this->talkDataFactory    = Mockery::mock(TalkDataFactory::class);
        $this->commentDataFactory = Mockery::mock(CommentDataFactory::class);
        $this->joindInClient      = new JoindInClient($this->client, $this->eventDataFactory, $this->talkDataFactory, $this->commentDataFactory);
    }

    public function testFetchZgPhpEvents()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testFetchTalksForEvent()
    {
        $this->markTestSkipped('Skipping');
    }

    public function testFetchCommentsForTalk()
    {
        $this->markTestSkipped('Skipping');
    }
}
