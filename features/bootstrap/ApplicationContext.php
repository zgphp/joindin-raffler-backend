<?php

use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\Repository\JoindInCommentRepository;
use App\Repository\JoindInEventRepository;
use App\Repository\JoindInTalkRepository;
use App\Repository\JoindInUserRepository;
use App\Service\JoindInCommentRetrieval;
use App\Service\JoindInEventRetrieval;
use App\Service\JoindInTalkRetrieval;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class ApplicationContext implements Context
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
     * @Given we have this meetups in the system
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
     * @Given we have this talks in the system
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

    /**
     * @When I fetch meetup data from Joind.in
     */
    public function iFetchMeetupDataFromJoindIn()
    {
        $service = $this->kernel->getContainer()->get(JoindInEventRetrieval::class);

        $service->fetch();
    }

    /**
     * @When I fetch meetup talks from Joind.in
     */
    public function iFetchMeetupTalksFromJoindIn()
    {
        $service = $this->kernel->getContainer()->get(JoindInTalkRetrieval::class);

        foreach ($this->getEventRepository()->findAll() as $event) {
            $service->fetch($event);
        }
    }

    /**
     * @When I fetch meetup talk comments from Joind.in
     */
    public function iFetchMeetupTalkCommentsFromJoindIn()
    {
        $service = $this->kernel->getContainer()->get(JoindInCommentRetrieval::class);

        foreach ($this->getTalkRepository()->findAll() as $talk) {
            $service->fetch($talk);
        }
    }

    /**
     * @Then there should be :count ZgPHP meetups in system
     */
    public function thereShouldBeZgphpMeetupsInSystem(int $count)
    {
        Assert::count($this->getEventRepository()->findAll(), $count);
    }

    /**
     * @Then there should be :count talks in system
     */
    public function thereShouldBeTalksInSystem(int $count)
    {
        Assert::count($this->getTalkRepository()->findAll(), $count);
    }

    /**
     * @Then there should be :count comment in system
     */
    public function thereShouldBeCommentInSystem(int $count)
    {
        Assert::count($this->getCommentRepository()->findAll(), $count);
    }

    private function getEntityManager(): EntityManager
    {
        return $this->kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    private function getEventRepository(): JoindInEventRepository
    {
        return $this->kernel->getContainer()->get(JoindInEventRepository::class);
    }

    private function getTalkRepository(): JoindInTalkRepository
    {
        return $this->kernel->getContainer()->get(JoindInTalkRepository::class);
    }

    private function getCommentRepository(): JoindInCommentRepository
    {
        return $this->kernel->getContainer()->get(JoindInCommentRepository::class);
    }

    private function getUserRepository(): JoindInUserRepository
    {
        return $this->kernel->getContainer()->get(JoindInUserRepository::class);
    }
}
