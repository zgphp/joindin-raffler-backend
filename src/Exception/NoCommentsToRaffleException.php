<?php

declare(strict_types=1);

namespace App\Exception;

use DomainException;

class NoCommentsToRaffleException extends DomainException
{
    public static function forRaffle(string $raffleId): self
    {
        $message = sprintf('There are no comments to raffle (RaffleID: %s)', $raffleId);

        return new self($message);
    }
}
