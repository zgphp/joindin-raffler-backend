<?php

namespace App\Service;

use App\JoindIn\EventDataFactory;
use GuzzleHttp\Client;

class JoindInClient
{
    /** @var Client */
    private $client;
    /** @var EventDataFactory */
    private $eventDataFactory;

    private $baseUrl = 'https://api.joind.in/v2.1';

    public function __construct(Client $client, EventDataFactory $eventDataFactory)
    {
        $this->client           = $client;
        $this->eventDataFactory = $eventDataFactory;
    }

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
}
