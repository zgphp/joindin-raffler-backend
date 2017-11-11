<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JoindInTalkRepository")
 * @ORM\Table(name="joindinTalks")
 */
class JoindInTalk implements \JsonSerializable
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
     * @ORM\Column(type="string", length=200)
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JoindInEvent", inversedBy="talks")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @var JoindInEvent
     */
    private $event;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTime
     */
    private $importedAt;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\JoindInComment", mappedBy="talk")
     */
    private $comments;

    public function __construct(int $id, string $title, JoindInEvent $event)
    {
        $this->id         = $id;
        $this->title      = $title;
        $this->event      = $event;
        $this->importedAt = new DateTime();

        $this->comments = new ArrayCollection();
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setEvent(JoindInEvent $event)
    {
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

    public function getCreatedAt(): DateTime
    {
        return $this->importedAt;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(JoindInComment $comment)
    {
        $this->comments->add($comment);
    }

    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'event'      => $this->event->jsonSerialize(),
            'importedAt' => $this->importedAt->format('c'),
        ];
    }
}
