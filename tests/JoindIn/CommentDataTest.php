<?php

namespace App\Tests\JoindIn;

use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\JoindIn\CommentData;
use App\JoindIn\UserData;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\JoindIn\CommentData
 * @group  unit
 */
class CommentDataTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var UserData */
    private $userData;
    /** @var JoindInTalk */
    private $talk;
    /** @var CommentData */
    private $commentData;

    public function setUp()
    {
        $this->userData    = new UserData(100001, 'username', 'Vanja Horvat');
        $this->talk        = new JoindInTalk(30000, 'Developing xyz', Mockery::mock(JoindInEvent::class));
        $this->commentData = new CommentData(1, 'comment', 5, $this->userData, $this->talk);
    }

    public function testGetId()
    {
        self::assertEquals(1, $this->commentData->getId());
    }

    public function testGetComment()
    {
        self::assertEquals('comment', $this->commentData->getComment());
    }

    public function testGetRating()
    {
        self::assertEquals(5, $this->commentData->getRating());
    }

    public function testGetUserData()
    {
        self::assertEquals($this->userData, $this->commentData->getUserData());
    }

    public function testGetUserId()
    {
        self::assertEquals(100001, $this->commentData->getUserId());
    }

    public function testGetUserName()
    {
        self::assertEquals('username', $this->commentData->getUserName());
    }

    public function testGetUserDisplayName()
    {
        self::assertEquals('Vanja Horvat', $this->commentData->getUserDisplayName());
    }

    public function testGetTalk()
    {
        self::assertEquals($this->talk, $this->commentData->getTalk());
    }
}
