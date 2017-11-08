<?php

declare(strict_types=1);

namespace App\JoindIn;

use App\Entity\JoindInEvent;

class TalkData
{
    /** @var int */
    private $id;
    /** @var string */
    private $title;
    /** @var JoindInEvent */
    private $event;

    public function __construct(int $id, string $title, JoindInEvent $event)
    {
        $this->id    = $id;
        $this->title = $title;
        $this->event = $event;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getEvent(): JoindInEvent
    {
        return $this->event;
    }
}
