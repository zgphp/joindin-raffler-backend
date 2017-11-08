<?php

declare(strict_types=1);

namespace spec\App\JoindIn;

use App\Entity\JoindInEvent;
use App\JoindIn\TalkData;
use App\JoindIn\TalkDataFactory;
use PhpSpec\ObjectBehavior;

class TalkDataFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TalkDataFactory::class);
    }

    public function it_should_return_talk_data_object(JoindInEvent $event)
    {
        $this->create(
            [
                'uri'        => 'https://api.joind.in/v2.1/talks/2345',
                'talk_title' => 'Talk about something',
            ],
            $event
        )->shouldReturnAnInstanceOf(TalkData::class);
    }
}
