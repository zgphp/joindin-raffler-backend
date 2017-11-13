<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
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
    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     *
     * @var bool
     */
    private $organizer;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\JoindInComment", mappedBy="user")
     */
    private $comments;

    public function __construct(int $id, string $username, string $displayName, bool $isOrganizer = false)
    {
        $this->id          = $id;
        $this->username    = $username;
        $this->displayName = $displayName;
        $this->organizer   = $isOrganizer;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName)
    {
        $this->displayName = $displayName;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'          => $this->id,
            'username'    => $this->username,
            'displayName' => $this->displayName,
        ];
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param JoindInComment $comment
     */
    public function addComment(JoindInComment $comment)
    {
        $this->comments->add($comment);
    }

    /**
     * @return bool
     */
    public function isOrganizer(): bool
    {
        return $this->organizer;
    }

    public function promoteToOrganizer()
    {
        $this->organizer = true;
    }
}
