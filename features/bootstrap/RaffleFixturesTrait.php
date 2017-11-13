<?php

declare(strict_types=1);

use App\Entity\JoindInComment;
use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\Entity\JoindInUser;
use App\Entity\Raffle;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

trait RaffleFixturesTrait
{
    /**
     * @Given we have a raffle with a single comment coming from :userName
     */
    public function weHaveARaffleWithASingleCommentComingFrom(string $userName)
    {
        $event   = new JoindInEvent(1, 'Meetup #1', new DateTime('today'));
        $talk    = new JoindInTalk(1, 'Talk on meetup #1', $event);
        $user    = new JoindInUser(1, $userName, $userName);
        $comment = new JoindInComment(1, 'Great talk', 5, $user, $talk);

        $event->addTalk($talk);
        $talk->addComment($comment);

        $this->raffleId = Uuid::uuid4()->toString();
        $raffle         = new Raffle($this->raffleId, new ArrayCollection([$event]));

        $this->getEntityManager()->persist($event);
        $this->getEntityManager()->persist($talk);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->persist($comment);
        $this->getEntityManager()->persist($raffle);

        $this->getEntityManager()->flush();
    }
}
