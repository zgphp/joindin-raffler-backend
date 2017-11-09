<?php

declare(strict_types=1);

namespace spec\App\JoindIn;

use App\JoindIn\EventData;
use PhpSpec\ObjectBehavior;

class EventDataSpec extends ObjectBehavior
{
    public function let(\DateTime $date)
    {
        $this->beConstructedWith(
            $id = 123,
            $name = 'ZgPHPMeetup',
            $date
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EventData::class);
    }
}
