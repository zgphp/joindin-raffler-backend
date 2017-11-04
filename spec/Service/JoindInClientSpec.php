<?php

namespace spec\App\Service;

use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\JoindIn\CommentData;
use App\JoindIn\CommentDataFactory;
use App\JoindIn\EventData;
use App\JoindIn\EventDataFactory;
use App\JoindIn\TalkData;
use App\JoindIn\TalkDataFactory;
use App\Service\JoindInClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\StreamInterface;

class JoindInClientSpec extends ObjectBehavior
{
    public function let(Client $client, EventDataFactory $eventDataFactory, TalkDataFactory $talkDataFactory, CommentDataFactory $commentDataFactory)
    {
        $this->beConstructedWith($client, $eventDataFactory, $talkDataFactory, $commentDataFactory);
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

    public function it_will_return_talks_for_given_event(
        Client $client,
        TalkDataFactory $talkDataFactory,
        Response $response,
        StreamInterface $body,
        JoindInEvent $event,
        TalkData $talkData
    ) {
        $event->getId()
            ->shouldBeCalled()
            ->willReturn(2345);

        $client->get('https://api.joind.in/v2.1/events/2345/talks')
            ->shouldBeCalled()
            ->willReturn($response);

        $response->getBody()
            ->shouldBeCalled()
            ->willReturn($body);

        $content = '
{
  "talks": [
    {
      "talk_title": "Talk about something",
      "speakers": [
        {
          "speaker_name": "Speaker Name"
        }
      ],
      "uri": "https://api.joind.in/v2.1/talks/2345"
    }
  ],
  "meta": {
    "count": 1,
    "total": 1,
    "this_page": "https://api.joind.in/v2.1/events/2345/talks?resultsperpage=20"
  }
}
        ';

        $body->getContents()
            ->shouldBeCalled()
            ->willReturn($content);

        $talkDataFactory->create(Argument::any(), $event)
            ->shouldBeCalled()
            ->willReturn($talkData);

        $this->fetchTalksForEvent($event)
            ->shouldReturn([$talkData]);
    }

    public function it_will_return_comments_for_given_talk(
        Client $client,
        CommentDataFactory $commentDataFactory,
        Response $response,
        StreamInterface $body,
        JoindInTalk $talk,
        CommentData $commentData
    ) {
        $talk->getId()
            ->shouldBeCalled()
            ->willReturn(2345);

        $client->get('https://api.joind.in/v2.1/talks/2345/comments')
            ->shouldBeCalled()
            ->willReturn($response);

        $response->getBody()
            ->shouldBeCalled()
            ->willReturn($body);

        $content = '
{
  "comments": [
    {
      "rating": 5,
      "comment": "Great talk",
      "user_display_name": "User Name",
      "username": "user_name",
      "uri": "https://api.joind.in/v2.1/talk_comments/34567",
      "user_uri": "https://api.joind.in/v2.1/users/12"
    }
  ]
}
        ';

        $body->getContents()
            ->shouldBeCalled()
            ->willReturn($content);

        $commentDataFactory->create(Argument::any(), $talk)
            ->shouldBeCalled()
            ->willReturn($commentData);

        $this->fetchCommentsForTalk($talk)
            ->shouldReturn([$commentData]);
    }
}
