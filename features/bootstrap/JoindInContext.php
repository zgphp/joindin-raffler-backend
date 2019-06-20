<?php

declare(strict_types=1);

use App\Service\JoindInCommentRetrieval;
use App\Service\JoindInEventRetrieval;
use App\Service\JoindInTalkRetrieval;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class JoindInContext implements Context
{
    use JoindInFixturesTrait;
    use HelperTrait;

    /** @var KernelInterface */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When I fetch meetup data from Joind.in
     */
    public function iFetchMeetupDataFromJoindIn(): void
    {
        /** @var JoindInEventRetrieval $service */
        $service = $this->getService(JoindInEventRetrieval::class);

        $service->fetch();
    }

    /**
     * @When I fetch meetup talks from Joind.in
     */
    public function iFetchMeetupTalksFromJoindIn(): void
    {
        /** @var JoindInTalkRetrieval $service */
        $service = $this->getService(JoindInTalkRetrieval::class);

        foreach ($this->getEventRepository()->findAll() as $event) {
            $service->fetch($event);
        }
    }

    /**
     * @When I fetch meetup talk comments from Joind.in
     */
    public function iFetchMeetupTalkCommentsFromJoindIn(): void
    {
        /** @var JoindInCommentRetrieval $service */
        $service = $this->getService(JoindInCommentRetrieval::class);

        foreach ($this->getTalkRepository()->findAll() as $talk) {
            $service->fetch($talk);
        }
    }

    /**
     * @Then there should be :count ZgPHP meetups in system
     */
    public function thereShouldBeZgphpMeetupsInSystem(int $count): void
    {
        Assert::count($this->getEventRepository()->findAll(), $count);
    }

    /**
     * @Then there should be :count talks in system
     */
    public function thereShouldBeTalksInSystem(int $count): void
    {
        Assert::count($this->getTalkRepository()->findAll(), $count);
    }

    /**
     * @Then there should be :count comment in system
     */
    public function thereShouldBeCommentInSystem(int $count): void
    {
        Assert::count($this->getCommentRepository()->findAll(), $count);
    }

    /**
     * @return object
     */
    protected function getService(string $name)
    {
        return $this->kernel->getContainer()->get($name);
    }

    /**
     * @When I fetch all meetups with talks and their comments from Joindin in one go
     */
    public function iFetchAllMeetupsWithTalksAndTheirCommentsFromJoindIn(): void
    {
        $this->iFetchMeetupDataFromJoindIn();
        $this->iFetchMeetupTalksFromJoindIn();
        $this->iFetchMeetupTalkCommentsFromJoindIn();
    }
}
