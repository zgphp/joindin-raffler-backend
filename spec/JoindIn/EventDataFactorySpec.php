<?php

namespace spec\App\JoindIn;

use App\JoindIn\EventData;
use App\JoindIn\EventDataFactory;
use PhpSpec\ObjectBehavior;

class EventDataFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EventDataFactory::class);
    }

    public function it_should_return_event_data_object()
    {
        $this->create(
            [
                'uri'        => 'https://api.joind.in/v2.1/events/123',
                'name'       => 'ZgPHP meetup #xxx',
                'start_date' => '2017-12-14T00:00:00+01:00',
                'end_date'   => '2017-12-14T00:00:00+01:00',
            ]
        )->shouldReturnAnInstanceOf(EventData::class);
    }
}
