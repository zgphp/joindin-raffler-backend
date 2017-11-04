<?php

use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\Repository\JoindInEventRepository;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class FixturesContext implements Context
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
            $event = new JoindInEvent((int) $row['id'], $row['title'], new DateTime($row['startDate']), new DateTime($row['endDate']));

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
        }
        $this->getEntityManager()->flush();
    }

    private function getEntityManager(): EntityManager
    {
        return $this->kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    private function getEventRepository(): JoindInEventRepository
    {
        return $this->kernel->getContainer()->get(JoindInEventRepository::class);
    }
}
