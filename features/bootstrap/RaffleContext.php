<?php

declare(strict_types=1);

use App\Entity\JoindInUser;
use App\Entity\Raffle;
use App\Exception\NoCommentsToRaffleException;
use App\Exception\NoEventsToRaffleException;
use Behat\Behat\Context\Context;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class RaffleContext implements Context
{
    use JoindInFixturesTrait;
    use RaffleFixturesTrait;
    use HelperTrait;

    /** @var KernelInterface */
    private $kernel;

    /** @var string UUID representing raffle */
    private $raffleId;
    /** @var JoindInUser|null */
    private $picked;
    private $exceptionHappened;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When organizer picks to raffle meetups: :eventIdList
     */
    public function organizerPicksToRaffleMeetups(string $eventIdList): void
    {
        $eventIds = explode(',', $eventIdList);

        $events = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($eventIds as $eventId) {
            $events->add($this->getEventRepository()->find($eventId));
        }

        $this->raffleId = Uuid::uuid4()->toString();

        try {
            $raffle = new Raffle($this->raffleId, $events);
        } catch (\Exception $exception) {
            $this->exceptionHappened = $exception;

            return;
        }

        $this->getEntityManager()->persist($raffle);
        $this->getEntityManager()->flush();
    }

    /**
     * @Then we get an exception for a raffle with no meetups
     */
    public function weGetAnExceptionForARaffleWithNoMeetups(): void
    {
        try {
            Raffle::create([]);

            throw new Exception('Raffling was supposed to throw an error');
        } catch (NoEventsToRaffleException $exception) {
            //We expect this exception to happen as there are no comments eligible for raffling.
            return;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @When I pick a winner
     * @When I pick another winner
     */
    public function wePick(): void
    {
        $raffle = $this->loadRaffle($this->raffleId);

        $this->picked = $raffle->pick();
    }

    /**
     * @When I confirm him or her as a winner
     */
    public function iConfirmHimOrHerAsAWinner(): void
    {
        $raffle = $this->loadRaffle($this->raffleId);

        if (null === $this->picked) {
            throw new \Exception('There is no picked user');
        }

        $raffle->userWon($this->picked);

        $this->getEntityManager()->flush();
    }

    /**
     * @When I mark him or her as a no show
     */
    public function iMarkHimOrHerAsANoShow(): void
    {
        $raffle = $this->loadRaffle($this->raffleId);

        if (null === $this->picked) {
            throw new \Exception('There is no picked user');
        }

        $raffle->userIsNoShow($this->picked);

        $this->getEntityManager()->flush();
    }

    /**
     * @When user :user wins
     */
    public function userWins(JoindInUser $user): void
    {
        $raffle = $this->loadRaffle($this->raffleId);

        $raffle->userWon($user);

        $this->getEntityManager()->flush();
    }

    /**
     * @When user :user is no show
     */
    public function userIsNoShow(JoindInUser $user): void
    {
        $raffle = $this->loadRaffle($this->raffleId);

        $raffle->userIsNoShow($user);

        $this->getEntityManager()->flush();
    }

    /**
     * @Then there should be :count events on the raffle
     */
    public function thereShouldBeEventsOnTheRaffle(int $count): void
    {
        $raffle = $this->loadRaffle($this->raffleId);

        Assert::count($raffle->getEvents(), $count);
    }

    /**
     * @Then there should be :count comments on the raffle
     */
    public function thereShouldBeCommentsOnTheRaffle(int $count): void
    {
        $raffle = $this->loadRaffle($this->raffleId);

        Assert::count($raffle->getCommentsEligibleForRaffling(), $count);
    }

    /**
     * @Then we should get one of :userNames as a winner
     */
    public function weShouldGetOneOfAsAWinner(string $userNames): void
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
    public function userShouldBeTimesInTheList(JoindInUser $user, int $count): void
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
    public function weShouldGetBackOneOfTheMembersThatLeftFeedback(): void
    {
        Assert::notEmpty($this->picked);
    }

    /**
     * @Then we should get :user as a winner
     */
    public function weShouldGetAsAWinner(JoindInUser $user): void
    {
        Assert::eq($user, $this->picked);
    }

    /**
     * @Then we should have :count eligible comment for next prize
     * @Then we should have :count eligible comments for next prize
     */
    public function weShouldHaveEligibleCommentForNextPrize(int $count): void
    {
        $raffle = $this->loadRaffle($this->raffleId);

        Assert::count($raffle->getCommentsEligibleForRaffling()->getValues(), $count);
    }

    /**
     * @Then we cannot continue raffling
     */
    public function weCannotContinueRaffling(): void
    {
        try {
            $raffle = $this->loadRaffle($this->raffleId);

            $raffle->pick();

            throw new Exception('Raffling was supposed to throw an error');
        } catch (NoCommentsToRaffleException $exception) {
            //We expect this exception to happen as there are no comments eligible for raffling.
            return;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @Then we get an exception for a raffle with no comments
     */
    public function weGetAnExceptionForARaffleWithNoComments(): void
    {
        Assert::notNull($this->exceptionHappened);
    }

    /**
     * @Then winners comments should not be eligible for further raffling
     */
    public function winnersCommentsShouldNotBeEligibleForFurtherRaffling(): void
    {
        if (null !== $this->picked) {
            $winnersComments = $this->picked->getComments();
            $raffle          = $this->loadRaffle($this->raffleId);
            foreach ($winnersComments as $comment) {
                Assert::false($raffle->getCommentsEligibleForRaffling()->contains($comment));
            }
        }
    }

    /**
     * @return object
     */
    protected function getService(string $name)
    {
        return $this->kernel->getContainer()->get($name);
    }
}
