<?php

use App\Entity\JoindInUser;
use Behat\Behat\Context\Context;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class ApiContext extends BaseContext
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var string
     */
    private $testApiUrl = 'http://test.raffler.loc:8000';

    /** @var string|null */
    private $raffleId;

    /** @var array|null */
    private $picked;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When I fetch meetup data from Joind.in
     */
    public function iFetchMeetupDataFromJoindIn()
    {
        $this->apiGetJson('/joindin/events/fetch');
    }

    /**
     * @When I fetch meetup talks from Joind.in
     */
    public function iFetchMeetupTalksFromJoindIn()
    {
        $this->apiGetJson('/joindin/talks/fetch');
    }

    /**
     * @When I fetch meetup talk comments from Joind.in
     */
    public function iFetchMeetupTalkCommentsFromJoindIn()
    {
        $this->apiGetJson('/joindin/comments/fetch');
    }

    /**
     * @When organizer picks to raffle meetups: :eventIdList
     */
    public function organizerPicksToRaffleMeetups(string $eventIdList)
    {
        $options = [
            'json' => ['events' => explode(',', $eventIdList)],
        ];

        $data = $this->apiPostJson('/api/raffle/start', $options);

        $this->raffleId = $data;
    }

    /**
     * @When we pick
     */
    public function wePick()
    {
        $this->picked = $this->apiPostJson('/api/raffle/'.$this->raffleId.'/pick');
    }

    /**
     * @When user :user wins
     */
    public function userWins(JoindInUser $user)
    {
        $url = '/api/raffle/'.$this->raffleId.'/winner/'.$user->getId();

        Assert::eq('OK', $this->apiPostJson($url));
    }

    /**
     * @When user :user is no show
     */
    public function userIsNoShow(JoindInUser $user)
    {
        $url = '/api/raffle/'.$this->raffleId.'/no_show/'.$user->getId();

        Assert::eq('OK', $this->apiPostJson($url));
    }

    /**
     * @Then there should be :count ZgPHP meetups in system
     */
    public function thereShouldBeZgphpMeetupsInSystem(int $count)
    {
        Assert::count($this->apiGetJson('/joindin/events/'), $count);
    }

    /**
     * @Then there should be :count talks in system
     */
    public function thereShouldBeTalksInSystem(int $count)
    {
        Assert::count($this->apiGetJson('/joindin/talks/'), $count);
    }

    /**
     * @Then there should be :count comment in system
     */
    public function thereShouldBeCommentInSystem(int $count)
    {
        $data = $this->apiGetJson('/joindin/comments/');

        Assert::count($data, $count);
    }

    /**
     * @Then there should be :count events on the raffle
     */
    public function thereShouldBeEventsOnTheRaffle(int $count)
    {
        $data = $this->apiGetJson('/api/raffle/'.$this->raffleId);

        Assert::count($data['events'], $count);
    }

    /**
     * @Then there should be :count comments on the raffle
     */
    public function thereShouldBeCommentsOnTheRaffle(int $count)
    {
        Assert::count($this->apiGetJson('/api/raffle/'.$this->raffleId.'/comments'), $count);
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
        $data = $this->apiGetJson('/api/raffle/'.$this->raffleId.'/comments');

        $found = 0;

        foreach ($data as $comment) {
            if ($comment['user']['id'] === $user->getId()) {
                ++$found;
            }
        }

        Assert::eq($found, $count);
    }

    private function apiGetJson(string $url)
    {
        $response = $this->getGuzzle()->get($this->testApiUrl.$url);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function apiPostJson(string $url, array $options = [])
    {
        $response = $this->getGuzzle()->post($this->testApiUrl.$url, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function getGuzzle(): Client
    {
        return $this->getService(Client::class);
    }

    protected function getService(string $name)
    {
        return $this->kernel->getContainer()->get($name);
    }
}
