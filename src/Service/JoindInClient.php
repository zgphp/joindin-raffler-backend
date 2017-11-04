<?php

namespace App\Service;

use App\Entity\JoindInEvent;
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

    private $baseUrl = 'https://api.joind.in/v2.1';

    public function __construct(Client $client, EventDataFactory $eventDataFactory, TalkDataFactory $talkDataFactory)
    {
        $this->client           = $client;
        $this->eventDataFactory = $eventDataFactory;
        $this->talkDataFactory  = $talkDataFactory;
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
}
