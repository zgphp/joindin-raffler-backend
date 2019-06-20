<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JoindInCommentRepository")
 * @ORM\Table(name="joindinComments")
 */
class JoindInComment implements \JsonSerializable
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
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $comment;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JoindInUser", inversedBy="comments")
     *
     * @var JoindInUser
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JoindInTalk", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @var JoindInTalk
     */
    private $talk;

    public function __construct(int $id, string $comment, int $rating, JoindInUser $user, JoindInTalk $talk)
    {
        $this->id      = $id;
        $this->comment = $comment;
        $this->rating  = $rating;
        $this->user    = $user;
        $this->talk    = $talk;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function setUser(JoindInUser $user): void
    {
        $this->user = $user;
    }

    public function setTalk(JoindInTalk $talk): void
    {
        $this->talk = $talk;
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

    public function getUser(): JoindInUser
    {
        return $this->user;
    }

    public function getTalk(): JoindInTalk
    {
        return $this->talk;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'      => $this->id,
            'comment' => $this->comment,
            'rating'  => $this->rating,
            'user'    => $this->user->jsonSerialize(),
            'talk'    => $this->talk->jsonSerialize(),
        ];
    }
}
