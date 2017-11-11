<?php

namespace App\Tests\Service;

use App\Repository\JoindInEventRepository;
use App\Service\JoindInClient;
use App\Service\JoindInEventRetrieval;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\JoindInEventRetrieval
 * @group todo
 */
class JoindInEventRetrievalTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var MockInterface|JoindInClient */
    private $joindInClient;
    /** @var MockInterface|EntityManagerInterface */
    private $entityManager;
    /** @var MockInterface|JoindInEventRepository */
    private $eventRepository;
    /** @var JoindInEventRetrieval */
    private $joindInEventRetrieval;

    public function setUp()
    {
        $this->joindInClient         = Mockery::mock(JoindInClient::class);
        $this->entityManager         = Mockery::mock(EntityManagerInterface::class);
        $this->eventRepository       = Mockery::mock(JoindInEventRepository::class);
        $this->joindInEventRetrieval = new JoindInEventRetrieval($this->joindInClient, $this->entityManager, $this->eventRepository);
    }

    public function testFetch()
    {
        $this->markTestSkipped('Skipping');
    }
}
