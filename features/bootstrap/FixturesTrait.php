<?php

use App\Entity\JoindInComment;
use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\Entity\JoindInUser;
use Behat\Gherkin\Node\TableNode;

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
}
