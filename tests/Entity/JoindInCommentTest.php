<?php

namespace App\Tests\Entity;

use App\Entity\JoindInComment;
use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\Entity\JoindInUser;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\JoindInComment
 * @group  unit
 */
class JoindInCommentTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var MockInterface|JoindInUser */
    private $user;
    /** @var MockInterface|JoindInTalk */
    private $talk;
    /** @var JoindInComment */
    private $joindInComment;

    public function setUp(): void
    {
        $this->user           = Mockery::mock(JoindInUser::class);
        $this->talk           = Mockery::mock(JoindInTalk::class);
        $this->joindInComment = new JoindInComment(1, 'comment', 5, $this->user, $this->talk);
    }

    public function testGetId(): void
    {
        self::assertEquals(1, $this->joindInComment->getId());
    }

    public function testGetComment(): void
    {
        self::assertEquals('comment', $this->joindInComment->getComment());
    }

    public function testGetRating(): void
    {
        self::assertEquals(5, $this->joindInComment->getRating());
    }

    public function testGetUser(): void
    {
        self::assertEquals($this->user, $this->joindInComment->getUser());
    }

    public function testGetTalk(): void
    {
        self::assertEquals($this->talk, $this->joindInComment->getTalk());
    }

    public function testSetComment(): void
    {
        self::assertEquals('comment', $this->joindInComment->getComment());
        $this->joindInComment->setComment('changed comment');
        self::assertEquals('changed comment', $this->joindInComment->getComment());
    }

    public function testSetRating(): void
    {
        self::assertEquals(5, $this->joindInComment->getRating());
        $this->joindInComment->setRating(4);
        self::assertEquals(4, $this->joindInComment->getRating());
    }

    public function testSetUser(): void
    {
        self::assertEquals($this->user, $this->joindInComment->getUser());

        $newUser = new JoindInUser(1, 'username', 'user name');

        $this->joindInComment->setUser($newUser);

        self::assertEquals($newUser, $this->joindInComment->getUser());
    }

    public function testSetTalk(): void
    {
        self::assertEquals($this->talk, $this->joindInComment->getTalk());

        $newTalk = new JoindInTalk(1, 'Meetup #101', Mockery::mock(JoindInEvent::class));

        $this->joindInComment->setTalk($newTalk);

        self::assertEquals($newTalk, $this->joindInComment->getTalk());
    }

    public function testJsonSerialize(): void
    {
        // Arrange.
        $this->user->shouldReceive('jsonSerialize')->once()->andReturn(['user serialized data']);
        $this->talk->shouldReceive('jsonSerialize')->once()->andReturn(['talk serialized data']);

        // Act.
        $data = $this->joindInComment->jsonSerialize();

        // Assert.
        $expected = [
            'id'      => 1,
            'comment' => 'comment',
            'rating'  => 5,
            'user'    => ['user serialized data'],
            'talk'    => ['talk serialized data'],
        ];

        self::assertEquals($expected, $data);
    }
}
