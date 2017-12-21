<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class JoindInApiContext implements Context
{
    use JoindInFixturesTrait;
    use HelperTrait;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /** @var string */
    private $testApiUrl = 'http://test.raffler.loc:8000/api';

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
     * @When I fetch all meetups with talks and their comments from Joindin in one go
     */
    public function iFetchAllMeetupsWithTalksAndTheirCommentsFromJoindIn()
    {
        $this->apiGetJson('/joindin/fetch/all');
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

    private function apiGetJson(string $url)
    {
        $guzzle   = $this->getService(Client::class);
        $response = $guzzle->get($this->testApiUrl.$url);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function getService(string $name)
    {
        return $this->kernel->getContainer()->get($name);
    }
}
