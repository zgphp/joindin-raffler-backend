<?php

namespace App\JoindIn;

use DateTime;

class EventData
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var DateTime */
    private $startDate;
    /** @var DateTime */
    private $endDate;

    public function __construct(int $id, string $name, DateTime $startDate, DateTime $endDate)
    {
        $this->id        = $id;
        $this->name      = $name;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }
}
