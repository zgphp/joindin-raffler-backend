<?php

namespace App\JoindIn;

use App\Entity\JoindInTalk;
use Exception;

class CommentDataFactory
{
    public function create(array $input, JoindInTalk $talk): CommentData
    {
        return new CommentData(
            $this->extractIdFromUri($input['uri']),
            $input['comment'],
            (int) $input['rating'],
            new UserData(
                $this->extractUserIdFromUri($input['user_uri']),
                $input['username'],
                $input['user_display_name']
            ),
            $talk
        );
    }

    private function extractIdFromUri(string $uri): int
    {
        if (preg_match('|https://api.joind.in/v2.1/talk_comments/(?<id>[\d]*)$|', $uri, $matches)) {
            return (int) $matches['id'];
        }
        throw new Exception('Unparsable '.$uri);
    }

    private function extractUserIdFromUri(string $uri): int
    {
        if (preg_match('|https://api.joind.in/v2.1/users/(?<id>[\d]*)$|', $uri, $matches)) {
            return (int) $matches['id'];
        }
        throw new Exception('Unparsable '.$uri);
    }
}
