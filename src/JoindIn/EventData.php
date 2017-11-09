<?php

declare(strict_types=1);

namespace App\JoindIn;

use DateTime;

class EventData
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var DateTime */
    private $date;

    public function __construct(int $id, string $name, DateTime $date)
    {
        $this->id        = $id;
        $this->name      = $name;
        $this->date      = $date;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }
}
