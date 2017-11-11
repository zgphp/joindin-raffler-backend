<?php

namespace App\Tests\Service;

use App\Repository\JoindInCommentRepository;
use App\Repository\JoindInUserRepository;
use App\Service\JoindInClient;
use App\Service\JoindInCommentRetrieval;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\JoindInCommentRetrieval
 * @group  todo
 */
class JoindInCommentRetrievalTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var MockInterface|JoindInClient */
    private $joindInClient;
    /** @var MockInterface|EntityManagerInterface */
    private $entityManager;
    /** @var MockInterface|JoindInCommentRepository */
    private $commentRepository;
    /** @var MockInterface|JoindInUserRepository */
    private $userRepository;
    /** @var JoindInCommentRetrieval */
    private $joindInCommentRetrieval;

    public function setUp()
    {
        $this->joindInClient           = Mockery::mock(JoindInClient::class);
        $this->entityManager           = Mockery::mock(EntityManagerInterface::class);
        $this->commentRepository       = Mockery::mock(JoindInCommentRepository::class);
        $this->userRepository          = Mockery::mock(JoindInUserRepository::class);
        $this->joindInCommentRetrieval = new JoindInCommentRetrieval(
            $this->joindInClient,
            $this->entityManager,
            $this->commentRepository,
            $this->userRepository
        );
    }

    public function testFetch()
    {
        $this->markTestSkipped('Skipping');
    }
}
