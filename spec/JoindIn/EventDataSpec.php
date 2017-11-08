<?php

declare(strict_types=1);

namespace spec\App\JoindIn;

use App\JoindIn\EventData;
use PhpSpec\ObjectBehavior;

class EventDataSpec extends ObjectBehavior
{
    public function let(\DateTime $startDate, \DateTime $endDate)
    {
        $this->beConstructedWith(
            $id = 123,
            $name = 'ZgPHPMeetup',
            $startDate,
            $endDate
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EventData::class);
    }
}
