<?php

use App\Entity\JoindInUser;
use App\Repository\JoindInCommentRepository;
use App\Repository\JoindInEventRepository;
use App\Repository\JoindInUserRepository;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
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

    /** @var string */
    private $raffleId;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When I fetch meetup data from Joind.in
     */
    public function iFetchMeetupDataFromJoindIn()
    {
        $this->getGuzzle()->get('http://test.raffler.loc:8000/joindin/events/fetch');
    }

    /**
     * @When I fetch meetup talks from Joind.in
     */
    public function iFetchMeetupTalksFromJoindIn()
    {
        $this->getGuzzle()->get('http://test.raffler.loc:8000/joindin/talks/fetch');
    }

    /**
     * @When I fetch meetup talk comments from Joind.in
     */
    public function iFetchMeetupTalkCommentsFromJoindIn()
    {
        $this->getGuzzle()->get('http://test.raffler.loc:8000/joindin/comments/fetch');
    }

    /**
     * @When organizer picks to raffle meetups: :eventIdList
     */
    public function organizerPicksToRaffleMeetups(string $eventIdList)
    {
        $options = [
            'json' => ['events'=> explode(',', $eventIdList)],
        ];

        $response = $this->getGuzzle()->post('http://test.raffler.loc:8000/api/raffle/start', $options);

        $data = json_decode($response->getBody()->getContents(), true);

        $this->raffleId = $data;
    }

    /**
     * @var array|null
     */
    private $picked;

    /**
     * @When we pick
     */
    public function wePick()
    {
        $options =[];

        $response = $this->getGuzzle()->post('http://test.raffler.loc:8000/api/raffle/'.$this->raffleId.'/pick', $options);

        $this->picked = json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @When user :user wins
     */
    public function userWins(JoindInUser $user)
    {
        $options =[];
        $url     = 'http://test.raffler.loc:8000/api/raffle/'.$this->raffleId.'/winner/'.$user->getId();

        $response = $this->getGuzzle()->post($url, $options);

        json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @When user :user is no show
     */
    public function userIsNoShow(JoindInUser $user)
    {
        $options =[];
        $url     = 'http://test.raffler.loc:8000/api/raffle/'.$this->raffleId.'/no_show/'.$user->getId();

        $response = $this->getGuzzle()->post($url, $options);

        json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @Then there should be :count ZgPHP meetups in system
     */
    public function thereShouldBeZgphpMeetupsInSystem(int $count)
    {
        $response = $this->getGuzzle()->get('http://test.raffler.loc:8000/joindin/events/');

        $data = json_decode($response->getBody()->getContents(), true);

        Assert::count($data, $count);
    }

    /**
     * @Then there should be :count talks in system
     */
    public function thereShouldBeTalksInSystem(int $count)
    {
        $response = $this->getGuzzle()->get('http://test.raffler.loc:8000/joindin/talks/');

        $data = json_decode($response->getBody()->getContents(), true);

        Assert::count($data, $count);
    }

    /**
     * @Then there should be :count comment in system
     */
    public function thereShouldBeCommentInSystem(int $count)
    {
        $response = $this->getGuzzle()->get('http://test.raffler.loc:8000/joindin/comments/');

        $data = json_decode($response->getBody()->getContents(), true);

        Assert::count($data, $count);
    }

    /**
     * @Then there should be :count events on the raffle
     */
    public function thereShouldBeEventsOnTheRaffle(int $count)
    {
        $response = $this->getGuzzle()->get('http://test.raffler.loc:8000/api/raffle/'.$this->raffleId);

        $data = json_decode($response->getBody()->getContents(), true);

        Assert::count($data['events'], $count);
    }

    /**
     * @Then there should be :count comments on the raffle
     */
    public function thereShouldBeCommentsOnTheRaffle(int $count)
    {
        $response = $this->getGuzzle()->get('http://test.raffler.loc:8000/api/raffle/'.$this->raffleId.'/comments');

        $data = json_decode($response->getBody()->getContents(), true);

        Assert::count($data, $count);
    }

    /**
     * @Then we should get back one of the members that left feedback
     */
    public function weShouldGetBackOneOfTheMembersThatLeftFeedback()
    {
        Assert::notNull($this->picked);
    }

    /**
     * @Then :user user should be :count times in the list
     */
    public function userShouldBeTimesInTheList(JoindInUser $user, int $count)
    {
        $response = $this->getGuzzle()->get('http://test.raffler.loc:8000/api/raffle/'.$this->raffleId.'/comments');

        $data = json_decode($response->getBody()->getContents(), true);

        $found  = 0;

        foreach ($data as $comment) {
            if ($comment['user']['id'] === $user->getId()) {
                ++$found;
            }
        }

        Assert::eq($found, $count);
    }

    /**
     * @Transform :user
     */
    public function castToUser(string $username): JoindInUser
    {
        return $this->getUserRepository()->findOneByUsername($username);
    }

    private function getGuzzle(): Client
    {
        return $this->kernel->getContainer()->get(Client::class);
    }

    private function getEntityManager(): EntityManager
    {
        return $this->kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    private function getEventRepository(): JoindInEventRepository
    {
        return $this->kernel->getContainer()->get(JoindInEventRepository::class);
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
