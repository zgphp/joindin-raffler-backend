<?php

declare(strict_types=1);

namespace App\JoindIn;

use App\Entity\JoindInEvent;

class TalkDataFactory
{
    public function create(array $input, JoindInEvent $event): TalkData
    {
        return new TalkData(
            $this->extractIdFromUri($input['uri']),
            $input['talk_title'],
            $event
        );
    }

    private function extractIdFromUri(string $uri): int
    {
        if (preg_match('|https://api.joind.in/v2.1/talks/(?<id>[\d]*)$|', $uri, $matches)) {
            return (int) $matches['id'];
        }
        throw new \Exception('Unparsable '.$uri);
    }
}
