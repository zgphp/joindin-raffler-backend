<?php

use App\Entity\JoindInUser;
use App\Entity\Raffle;
use App\Service\JoindInCommentRetrieval;
use App\Service\JoindInEventRetrieval;
use App\Service\JoindInTalkRetrieval;
use Behat\Behat\Context\Context;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class ApplicationContext extends BaseContext
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var string UUID representing raffle
     */
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
        $service = $this->getService(JoindInEventRetrieval::class);

        $service->fetch();
    }

    /**
     * @When I fetch meetup talks from Joind.in
     */
    public function iFetchMeetupTalksFromJoindIn()
    {
        $service = $this->getService(JoindInTalkRetrieval::class);

        foreach ($this->getEventRepository()->findAll() as $event) {
            $service->fetch($event);
        }
    }

    /**
     * @When I fetch meetup talk comments from Joind.in
     */
    public function iFetchMeetupTalkCommentsFromJoindIn()
    {
        $service = $this->getService(JoindInCommentRetrieval::class);

        foreach ($this->getTalkRepository()->findAll() as $talk) {
            $service->fetch($talk);
        }
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

    private $picked;

    /**
     * @When we pick
     */
    public function wePick()
    {
        $raffle = $this->loadRaffle($this->raffleId);

        $this->picked = $raffle->pick();
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

    protected function getService(string $name)
    {
        return $this->kernel->getContainer()->get($name);
    }
}
