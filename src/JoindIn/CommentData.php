<?php

namespace App\JoindIn;

use App\Entity\JoindInTalk;

class CommentData
{
    /** @var int */
    private $id;
    /** @var string */
    private $comment;
    /** @var int */
    private $rating;
    /** @var UserData */
    private $userData;
    /** @var JoindInTalk */
    private $talk;

    public function __construct(int $id, string $comment, int $rating, UserData $userData, JoindInTalk $talk)
    {
        $this->id       = $id;
        $this->comment  = $comment;
        $this->rating   = $rating;
        $this->userData = $userData;
        $this->talk     = $talk;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getUserData(): UserData
    {
        return $this->userData;
    }

    public function getUserId(): int
    {
        return $this->userData->getId();
    }

    public function getUserName(): string
    {
        return $this->userData->getUsername();
    }

    public function getUserDisplayName(): string
    {
        return $this->userData->getDisplayName();
    }

    public function getTalk(): JoindInTalk
    {
        return $this->talk;
    }
}
