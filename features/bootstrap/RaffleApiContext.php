<?php

declare(strict_types=1);

use App\Entity\JoindInUser;
use Behat\Behat\Context\Context;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class RaffleApiContext implements Context
{
    use JoindInFixturesTrait;
    use RaffleFixturesTrait;
    use HelperTrait;

    /** @var KernelInterface */
    private $kernel;

    /** @var string|null */
    private $raffleId;
    /** @var array|null */
    private $picked;

    /** @var string */
    private $testApiUrl = 'http://test.raffler.loc:8000/api';

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When organizer picks to raffle meetups: :eventIdList
     */
    public function organizerPicksToRaffleMeetups(string $eventIdList): void
    {
        $options = [
            'json' => ['events' => explode(',', $eventIdList)],
        ];

        $data           = $this->apiPostJson('/raffle/start', $options);
        $this->raffleId = $data;
    }

    /**
     * @Then we get an exception for a raffle with no meetups
     */
    public function weGetAnExceptionForARaffleWithNoMeetups(): void
    {
        $options = [
            'json' => ['events' => []],
        ];

        $data = $this->apiPostJson('/raffle/start', $options);

        Assert::contains($data, 'There were no events selected to raffle (RaffleID:');
    }

    /**
     * @When I pick a winner
     * @When I pick another winner
     */
    public function wePick(): void
    {
        $this->picked = $this->apiPostJson('/raffle/'.$this->raffleId.'/pick');
    }

    /**
     * @When I confirm him or her as a winner
     */
    public function iConfirmHimOrHerAsAWinner(): void
    {
        $url = '/raffle/'.$this->raffleId.'/winner/'.$this->picked['id'];

        Assert::eq('OK', $this->apiPostJson($url));
    }

    /**
     * @When I mark him or her as a no show
     */
    public function iMarkHimOrHerAsANoShow(): void
    {
        $url = '/raffle/'.$this->raffleId.'/no_show/'.$this->picked['id'];

        Assert::eq('OK', $this->apiPostJson($url));
    }

    /**
     * @When user :user wins
     */
    public function userWins(JoindInUser $user): void
    {
        $url = '/raffle/'.$this->raffleId.'/winner/'.$user->getId();

        Assert::eq('OK', $this->apiPostJson($url));
    }

    /**
     * @When user :user is no show
     */
    public function userIsNoShow(JoindInUser $user): void
    {
        $url = '/raffle/'.$this->raffleId.'/no_show/'.$user->getId();

        Assert::eq('OK', $this->apiPostJson($url));
    }

    /**
     * @Then there should be :count events on the raffle
     */
    public function thereShouldBeEventsOnTheRaffle(int $count): void
    {
        $data = $this->apiGetJson('/raffle/'.$this->raffleId);

        Assert::count($data['events'], $count);
    }

    /**
     * @Then there should be :count comments on the raffle
     */
    public function thereShouldBeCommentsOnTheRaffle(int $count): void
    {
        Assert::count($this->apiGetJson('/raffle/'.$this->raffleId.'/comments'), $count);
    }

    /**
     * @Then we should get one of :userNames as a winner
     */
    public function weShouldGetOneOfAsAWinner(string $userNames): void
    {
        $users = $this->loadMultipleUsers($userNames);

        foreach ($users as $user) {
            if ($user->getId() === $this->picked['id']) {
                return;
            }
        }

        throw new Exception('Picked user is not in the expected list');
    }

    /**
     * @Then we should get back one of the members that left feedback
     */
    public function weShouldGetBackOneOfTheMembersThatLeftFeedback(): void
    {
        Assert::notNull($this->picked);
    }

    /**
     * @Then :user user should be :count times in the list
     */
    public function userShouldBeTimesInTheList(JoindInUser $user, int $count): void
    {
        $data = $this->apiGetJson('/raffle/'.$this->raffleId.'/comments');

        $found = 0;

        foreach ($data as $comment) {
            if ($comment['user']['id'] === $user->getId()) {
                ++$found;
            }
        }

        Assert::eq($found, $count);
    }

    /**
     * @Then we should get :user as a winner
     */
    public function weShouldGetAsAWinner(JoindInUser $user): void
    {
        Assert::eq($user->getId(), $this->picked['id']);
    }

    /**
     * @Then we should have :count eligible comment for next prize
     * @Then we should have :count eligible comments for next prize
     */
    public function weShouldHaveEligibleCommentForNextPrize(int $count): void
    {
        $data = $this->apiGetJson('/raffle/'.$this->raffleId.'/comments');

        Assert::count($data, $count);
    }

    /**
     * @Then winners comments should not be eligible for further raffling
     */
    public function winnersCommentsShouldNotBeEligibleForFurtherRaffling(): void
    {
        $eligibleComments = $this->apiGetJson('/raffle/'.$this->raffleId.'/comments');

        $noneligibleComments = $this->apiGetJson('/raffle/'.$this->raffleId.'/noneligible_comments');

        $eligibleCommentsIds = [];
        foreach ($eligibleComments as $comment) {
            $eligibleCommentsIds[] = $comment['id'];
        }

        foreach ($noneligibleComments as $noneligibleComment) {
            if (array_key_exists($noneligibleComment['id'], $eligibleCommentsIds)) {
                throw new Exception('comment both eligible and non eligible');
            }
        }
    }

    /**
     * @Then we cannot continue raffling
     */
    public function weCannotContinueRaffling(): void
    {
        $response = $this->apiPostJson('/raffle/'.$this->raffleId.'/pick');

        Assert::true($response['error']);
    }

    /**
     * @Then we get an exception for a raffle with no comments
     */
    public function weGetAnExceptionForARaffleWithNoComments(): void
    {
        Assert::eq($this->raffleId, '');
    }

    /** @return mixed */
    private function apiGetJson(string $url)
    {
        $response = $this->getGuzzle()->get($this->testApiUrl.$url);

        return json_decode($response->getBody()->getContents(), true);
    }

    /** @return mixed */
    private function apiPostJson(string $url, array $options = [])
    {
        try {
            $response = $this->getGuzzle()->post($this->testApiUrl.$url, $options);

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $ex) {
            return $ex->getMessage();
        }
    }

    private function getGuzzle(): Client
    {
        return $this->getService(Client::class);
    }

    /**
     * @return object
     */
    protected function getService(string $name)
    {
        return $this->kernel->getContainer()->get($name);
    }
}
