<?php

namespace spec\App\JoindIn;

use App\Entity\JoindInTalk;
use App\JoindIn\CommentData;
use App\JoindIn\UserData;
use PhpSpec\ObjectBehavior;

class CommentDataSpec extends ObjectBehavior
{
    public function let(UserData $userData, JoindInTalk $talk)
    {
        $this->beConstructedWith(
            $id = 34567,
            $comment = 'Great talk',
            $rating = 5,
            $userData,
            $talk
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CommentData::class);
    }
}
