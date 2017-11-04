<?php

namespace spec\App\JoindIn;

use App\JoindIn\UserData;
use PhpSpec\ObjectBehavior;

class UserDataSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            $id = 12,
            $username = 'user_name',
            $displayName = 'User Name'
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserData::class);
    }
}
