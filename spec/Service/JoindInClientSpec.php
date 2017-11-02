<?php

namespace spec\App\Service;

use App\JoindIn\EventData;
use App\JoindIn\EventDataFactory;
use App\Service\JoindInClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\StreamInterface;

class JoindInClientSpec extends ObjectBehavior
{
    public function let(Client $client, EventDataFactory $eventDataFactory)
    {
        $this->beConstructedWith($client, $eventDataFactory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(JoindInClient::class);
    }

    public function it_will_return_zgphp_events(
        Client $client,
        Response $response,
        StreamInterface $body,
        EventDataFactory $eventDataFactory,
        EventData $eventData
    ) {
        $client->get('https://api.joind.in/v2.1/events?title=zgphp&resultsperpage=30')
            ->shouldBeCalled()
            ->willReturn($response);

        $response->getBody()
            ->shouldBeCalled()
            ->willReturn($body);

        $content = '
        {
  "events": [
    {
      "name": "ZgPHP 2017/12 meetup",
      "start_date": "2017-12-14T00:00:00+01:00",
      "end_date": "2017-12-14T00:00:00+01:00",
      "uri": "https://api.joind.in/v2.1/events/6676"
    }
  ],
  "meta": {
    "count": 1,
    "total": 1,
    "this_page": "https://api.joind.in/v2.1/events?title=zgphp&resultsperpage=20"
  }
}
        ';

        $body->getContents()
            ->shouldBeCalled()
            ->willReturn($content);

        $eventDataFactory->create(Argument::any())
            ->shouldBeCalled()
            ->willReturn($eventData);

        $this->fetchZgPhpEvents()
            ->shouldReturn([$eventData]);
    }

    public function it_will_return_empty_array_when_no_events_found(
        Client $client,
        Response $response,
        StreamInterface $body
    ) {
        $client->get('https://api.joind.in/v2.1/events?title=zgphp&resultsperpage=30')
            ->shouldBeCalled()
            ->willReturn($response);

        $response->getBody()
            ->shouldBeCalled()
            ->willReturn($body);

        $body->getContents()
            ->shouldBeCalled()
            ->willReturn('{"events": []}');

        $this->fetchZgPhpEvents()
            ->shouldReturn([]);
    }
}
