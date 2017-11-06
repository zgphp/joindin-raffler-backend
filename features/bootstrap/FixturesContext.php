<?php

use App\Entity\JoindInComment;
use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\Entity\JoindInUser;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class FixturesContext extends BaseContext
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given there are no meetups in the system
     */
    public function thereAreNoMeetupsInTheSystem()
    {
        foreach ($this->getEventRepository()->findAll() as $joindInEvent) {
            foreach ($joindInEvent->getTalks() as $joindInTalk) {
                foreach ($joindInTalk->getComments() as $joindInComment) {
                    $this->getEntityManager()->remove($joindInComment);
                }

                $this->getEntityManager()->remove($joindInTalk);
            }

            $this->getEntityManager()->remove($joindInEvent);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @Given we have these meetups in the system
     */
    public function weHaveThisMeetupsInTheSystem(TableNode $table)
    {
        $this->thereAreNoMeetupsInTheSystem();

        foreach ($table as $row) {
            $event = new JoindInEvent(
                (int) $row['id'],
                $row['title'],
                new DateTime($row['startDate']),
                new DateTime($row['endDate'])
            );

            $this->getEntityManager()->persist($event);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @Given we have these talks in the system
     */
    public function weHaveThisTalksInTheSystem(TableNode $table)
    {
        foreach ($table as $row) {
            $event = $this->getEventRepository()->find($row['eventId']);

            $talk = new JoindInTalk(
                (int) $row['id'],
                $row['title'],
                $event,
                new DateTime($row['importedAt'])
            );

            $this->getEntityManager()->persist($talk);
            $event->addTalk($talk);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @Given there are no raffles in the system
     */
    public function thereAreNoRafflesInTheSystem()
    {
        foreach ($this->getRaffleRepository()->findAll() as $raffle) {
            $this->getEntityManager()->remove($raffle);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @Given there are no users in the system
     */
    public function thereAreNoUsersInTheSystem()
    {
        foreach ($this->getUserRepository()->findAll() as $user) {
            $this->getEntityManager()->remove($user);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @Given we have these users in the system
     */
    public function weHaveThisUsersInTheSystem(TableNode $table)
    {
        $this->thereAreNoUsersInTheSystem();

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

    protected function getService(string $name)
    {
        return $this->kernel->getContainer()->get($name);
    }
}
