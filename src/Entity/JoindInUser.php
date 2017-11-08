<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JoindInUserRepository")
 * @ORM\Table(name="joindinUsers")
 */
class JoindInUser implements \JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=200)
     *
     * @var string
     */
    private $displayName;

    public function __construct(int $id, string $username, string $displayName)
    {
        $this->id          = $id;
        $this->username    = $username;
        $this->displayName = $displayName;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function setDisplayName(string $displayName)
    {
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

    public function jsonSerialize(): array
    {
        return [
            'id'          => $this->id,
            'username'    => $this->username,
            'displayName' => $this->displayName,
        ];
    }
}
