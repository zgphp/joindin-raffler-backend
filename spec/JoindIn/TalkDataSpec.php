<?php

namespace spec\App\JoindIn;

use App\Entity\JoindInEvent;
use App\JoindIn\TalkData;
use PhpSpec\ObjectBehavior;

class TalkDataSpec extends ObjectBehavior
{
    public function let(JoindInEvent $event)
    {
        $this->beConstructedWith(
            $id = 2345,
            $title = 'Talk about something',
            $event
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TalkData::class);
    }
}
