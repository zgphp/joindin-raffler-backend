<?php

use App\Repository\JoindInEventRepository;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class ApiContext implements Context
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
            $this->getEntityManager()->remove($joindInEvent);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @When I fetch meetup data from Joind.in
     */
    public function iFetchMeetupDataFromJoindIn()
    {
        $this->getGuzzle()->get('http://test.raffler.loc:8000/joindin/fetch');
    }

    /**
     * @Then there should be :count ZgPHP meetups in system
     */
    public function thereShouldBeZgphpMeetupsInSystem(int $count)
    {
        $response = $this->getGuzzle()->get('http://test.raffler.loc:8000/joindin/list');

        $data = json_decode($response->getBody()->getContents(), true);

        Assert::count($data, $count);
    }

    private function getGuzzle(): \GuzzleHttp\Client
    {
        return $this->kernel->getContainer()->get(\GuzzleHttp\Client::class);
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
