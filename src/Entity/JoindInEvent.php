<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JoindInEventRepository")
 * @ORM\Table(name="joindinEvents")
 */
class JoindInEvent implements \JsonSerializable
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
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTime
     */
    private $date;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\JoindInTalk", mappedBy="event")
     */
    private $talks;

    public function __construct(int $id, string $name, DateTime $date)
    {
        $this->id        = $id;
        $this->name      = $name;
        $this->date      = $date;

        $this->talks = new ArrayCollection();
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setDate(DateTime $date)
    {
        $this->date = $date;
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

    public function getTalks(): Collection
    {
        return $this->talks;
    }

    public function addTalk(JoindInTalk $talk)
    {
        $this->talks->add($talk);
    }

    public function jsonSerialize(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'date'      => $this->date->format('c'),
        ];
    }
}
