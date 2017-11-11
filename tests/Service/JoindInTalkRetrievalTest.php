<?php

namespace App\Tests\Service;

use App\Repository\JoindInTalkRepository;
use App\Service\JoindInClient;
use App\Service\JoindInTalkRetrieval;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\JoindInTalkRetrieval
 * @group todo
 */
class JoindInTalkRetrievalTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var MockInterface|JoindInClient */
    private $joindInClient;
    /** @var MockInterface|EntityManagerInterface */
    private $entityManager;
    /** @var MockInterface|JoindInTalkRepository */
    private $talkRepository;
    /** @var JoindInTalkRetrieval */
    private $joindInTalkRetrieval;

    public function setUp()
    {
        $this->joindInClient        = Mockery::mock(JoindInClient::class);
        $this->entityManager        = Mockery::mock(EntityManagerInterface::class);
        $this->talkRepository       = Mockery::mock(JoindInTalkRepository::class);
        $this->joindInTalkRetrieval = new JoindInTalkRetrieval($this->joindInClient, $this->entityManager, $this->talkRepository);
    }

    public function testFetch()
    {
        $this->markTestSkipped('Skipping');
    }
}
