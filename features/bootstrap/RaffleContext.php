<?php

use App\Entity\JoindInUser;
use App\Entity\Raffle;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class RaffleContext extends BaseContext
{
    use FixturesTrait;

    /** @var KernelInterface */
    private $kernel;

    /** @var string UUID representing raffle */
    private $raffleId;
    /** @var JoindInUser|null */
    private $picked;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When organizer picks to raffle meetups: :eventIdList
     */
    public function organizerPicksToRaffleMeetups(string $eventIdList)
    {
        $eventIds = explode(',', $eventIdList);

        $events = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($eventIds as $eventId) {
            $events->add($this->getEventRepository()->find($eventId));
        }

        $this->raffleId = Uuid::uuid4()->toString();

        $raffle = new Raffle($this->raffleId, $events);

        $this->getEntityManager()->persist($raffle);
        $this->getEntityManager()->flush();
    }

    /**
     * @When I pick a winner
     */
    public function wePick()
    {
        $raffle = $this->loadRaffle($this->raffleId);

        $this->picked = $raffle->pick();
    }

    /**
     * @When user :user wins
     */
    public function userWins(JoindInUser $user)
    {
        $raffle = $this->loadRaffle($this->raffleId);

        $raffle->userWon($user);

        $this->getEntityManager()->flush();
    }

    /**
     * @When user :user is no show
     */
    public function userIsNoShow(JoindInUser $user)
    {
        $raffle = $this->loadRaffle($this->raffleId);

        $raffle->userIsNoShow($user);

        $this->getEntityManager()->flush();
    }

    /**
     * @Then there should be :count events on the raffle
     */
    public function thereShouldBeEventsOnTheRaffle(int $count)
    {
        $raffle = $this->loadRaffle($this->raffleId);

        Assert::count($raffle->getEvents(), $count);
    }

    /**
     * @Then there should be :count comments on the raffle
     */
    public function thereShouldBeCommentsOnTheRaffle(int $count)
    {
        $raffle = $this->loadRaffle($this->raffleId);

        Assert::count($raffle->getCommentsEligibleForRaffling(), $count);
    }

    /**
     * @Then we should get one of :userNames as a winner
     */
    public function weShouldGetOneOfAsAWinner(string $userNames)
    {
        $users = $this->loadMultipleUsers($userNames);

        foreach ($users as $user) {
            if ($user === $this->picked) {
                return;
            }
        }

        throw new Exception('Picked user is not in the expected list');
    }

    /**
     * @Then :user user should be :count times in the list
     */
    public function userShouldBeTimesInTheList(JoindInUser $user, int $count)
    {
        $raffle = $this->loadRaffle($this->raffleId);
        $found  = 0;

        foreach ($raffle->getCommentsEligibleForRaffling() as $comment) {
            if ($comment->getUser() === $user) {
                ++$found;
            }
        }

        Assert::eq($found, $count);
    }

    /**
     * @Then we should get back one of the members that left feedback
     */
    public function weShouldGetBackOneOfTheMembersThatLeftFeedback()
    {
        Assert::notEmpty($this->picked);
    }

    /**
     * @Then we should get :user as a winner
     */
    public function weShouldGetAsAWinner(JoindInUser $user)
    {
        Assert::eq($user, $this->picked);
    }

    protected function getService(string $name)
    {
        return $this->kernel->getContainer()->get($name);
    }
}
