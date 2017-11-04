<?php

namespace spec\App\JoindIn;

use App\Entity\JoindInTalk;
use App\JoindIn\CommentData;
use App\JoindIn\CommentDataFactory;
use PhpSpec\ObjectBehavior;

class CommentDataFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CommentDataFactory::class);
    }

    public function it_should_return_talk_data_object(JoindInTalk $talk)
    {
        $this->create(
            [
                'uri'              => 'https://api.joind.in/v2.1/talk_comments/34567',
                'comment'          => 'Great talk',
                'rating'           => 5,
                'user_uri'         => 'https://api.joind.in/v2.1/users/12',
                'username'         => 'user_name',
                'user_display_name'=> 'User Name',
            ],
            $talk
        )->shouldReturnAnInstanceOf(CommentData::class);
    }
}
