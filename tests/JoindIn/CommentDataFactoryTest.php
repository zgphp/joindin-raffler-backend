<?php

namespace App\Tests\JoindIn;

use App\Entity\JoindInTalk;
use App\JoindIn\CommentData;
use App\JoindIn\CommentDataFactory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\JoindIn\CommentDataFactory
 * @group  unit
 */
class CommentDataFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @var CommentDataFactory */
    private $commentDataFactory;

    public function setUp()
    {
        $this->commentDataFactory = new CommentDataFactory();
    }

    public function testCreate()
    {
        // Arrange.
        $input = [
            'rating'            => 5,
            'comment'           => 'A nice overview of ...',
            'user_display_name' => 'Vanja Horvat',
            'username'          => 'fake_vanja_horvat',
            'talk_title'        => 'Developing with ...',
            'created_date'      => '2017-10-26T15:38:21+02:00',
            'uri'               => 'https://api.joind.in/v2.1/talk_comments/1234567890',
            'verbose_uri'       => 'https://api.joind.in/v2.1/talk_comments/1234567890?verbose=yes',
            'talk_uri'          => 'https://api.joind.in/v2.1/talks/100000024680',
            'talk_comments_uri' => 'https://api.joind.in/v2.1/talks/100000024680/comments',
            'reported_uri'      => 'https://api.joind.in/v2.1/talk_comments/1234567890/reported',
            'user_uri'          => 'https://api.joind.in/v2.1/users/100000035791',
        ];

        $talk = Mockery::mock(JoindInTalk::class);

        // Act.
        $commentData = $this->commentDataFactory->create($input, $talk);

        // Assert.
        self::assertInstanceOf(CommentData::class, $commentData);
    }
}
