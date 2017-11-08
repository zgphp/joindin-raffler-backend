<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\JoindIn\CommentData;
use App\JoindIn\CommentDataFactory;
use App\JoindIn\EventData;
use App\JoindIn\EventDataFactory;
use App\JoindIn\TalkData;
use App\JoindIn\TalkDataFactory;
use GuzzleHttp\Client;

class JoindInClient
{
    /** @var Client */
    private $client;
    /** @var EventDataFactory */
    private $eventDataFactory;
    /** @var TalkDataFactory */
    private $talkDataFactory;
    /** @var CommentDataFactory */
    private $commentDataFactory;

    private $baseUrl = 'https://api.joind.in/v2.1';

    public function __construct(
        Client $client,
        EventDataFactory $eventDataFactory,
        TalkDataFactory $talkDataFactory,
        CommentDataFactory $commentDataFactory
    ) {
        $this->client             = $client;
        $this->eventDataFactory   = $eventDataFactory;
        $this->talkDataFactory    = $talkDataFactory;
        $this->commentDataFactory = $commentDataFactory;
    }

    /** @return EventData[] */
    public function fetchZgPhpEvents(): array
    {
        $url = $this->baseUrl.'/events?title=zgphp&resultsperpage=30';

        $response = $this->client->get($url);

        $raw = json_decode($response->getBody()->getContents(), true);

        $results = [];

        foreach ($raw['events'] as $item) {
            $results[] = $this->eventDataFactory->create($item);
        }

        return $results;
    }

    /** @return TalkData[] */
    public function fetchTalksForEvent(JoindInEvent $event): array
    {
        $url = $this->baseUrl.'/events/'.$event->getId().'/talks';

        $response = $this->client->get($url);

        $raw = json_decode($response->getBody()->getContents(), true);

        $results = [];

        foreach ($raw['talks'] as $item) {
            $results[] = $this->talkDataFactory->create($item, $event);
        }

        return $results;
    }

    /** @return CommentData[] */
    public function fetchCommentsForTalk(JoindInTalk $talk): array
    {
        $url = $this->baseUrl.'/talks/'.$talk->getId().'/comments';

        $response = $this->client->get($url);

        $raw = json_decode($response->getBody()->getContents(), true);

        $results = [];

        foreach ($raw['comments'] as $item) {
            if (false === empty($item['user_uri'])) {
                $results[] = $this->commentDataFactory->create($item, $talk);
            }
        }

        return $results;
    }
}
