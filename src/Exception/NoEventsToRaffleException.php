<?php

declare(strict_types=1);

namespace App\Exception;

use DomainException;

class NoEventsToRaffleException extends DomainException
{
    public static function forRaffle(string $raffleId): self
    {
        $message = sprintf('There were no events selected to raffle (RaffleID: %s)', $raffleId);

        return new self($message);
    }
}
