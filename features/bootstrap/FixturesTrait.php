<?php

declare(strict_types=1);

use App\Entity\JoindInComment;
use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\Entity\JoindInUser;
use App\Entity\Raffle;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

trait FixturesTrait
{
    /**
     * @Given we have these meetups in the system
     */
    public function weHaveTheseMeetupsInTheSystem(TableNode $table)
    {
        foreach ($table as $row) {
            $date  = new DateTime($row['date']);
            $event = new JoindInEvent((int) $row['id'], $row['title'], $date, $date);

            $this->getEntityManager()->persist($event);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @Given we have these talks in the system
     */
    public function weHaveTheseTalksInTheSystem(TableNode $table)
    {
        foreach ($table as $row) {
            $event = $this->getEventRepository()->find($row['eventId']);

            $talk = new JoindInTalk((int) $row['id'], $row['title'], $event, new DateTime());

            $this->getEntityManager()->persist($talk);
            $event->addTalk($talk);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @Given we have these users in the system
     */
    public function weHaveTheseUsersInTheSystem(TableNode $table)
    {
        foreach ($table as $row) {
            $user = new JoindInUser((int) $row['id'], $row['username'], $row['displayName']);

            $this->getEntityManager()->persist($user);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @Given we have each user commenting on each talk
     */
    public function weHaveEachUserCommentingOnEachTalk()
    {
        $users = $this->getUserRepository()->findAll();
        $talks = $this->getTalkRepository()->findAll();

        $cnt = 1;
        foreach ($users as $user) {
            foreach ($talks as $talk) {
                ++$cnt;
                $comment = new JoindInComment($cnt, 'Comment', 5, $user, $talk);

                $this->getEntityManager()->persist($comment);
                $talk->addComment($comment);
            }
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @Given we have a raffle with a single comment coming from :userName
     */
    public function weHaveARaffleWithASingleCommentComingFrom(string $userName)
    {
        $event   = new JoindInEvent(1, 'Meetup #1', new DateTime('today'), new DateTime('today'));
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
