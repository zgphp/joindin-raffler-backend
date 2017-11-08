<?php

declare(strict_types=1);

namespace App\JoindIn;

class UserData
{
    /** @var int */
    private $id;
    /** @var string */
    private $username;
    /** @var string */
    private $displayName;

    public function __construct(int $id, string $username, string $displayName)
    {
        $this->id          = $id;
        $this->username    = $username;
        $this->displayName = $displayName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }
}
