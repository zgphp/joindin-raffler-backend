<?php

use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class JoindInApiContext extends BaseContext
{
    use FixturesTrait;
    /**
     * @var KernelInterface
     */
    private $kernel;

    /** @var string|null */
    private $raffleId;
    /** @var array|null */
    private $picked;

    /** @var string */
    private $testApiUrl = 'http://test.raffler.loc:8000';

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When I fetch meetup data from Joind.in
     */
    public function iFetchMeetupDataFromJoindIn()
    {
        $this->apiGetJson('/api/joindin/events/fetch');
    }

    /**
     * @When I fetch meetup talks from Joind.in
     */
    public function iFetchMeetupTalksFromJoindIn()
    {
        $this->apiGetJson('/api/joindin/talks/fetch');
    }

    /**
     * @When I fetch meetup talk comments from Joind.in
     */
    public function iFetchMeetupTalkCommentsFromJoindIn()
    {
        $this->apiGetJson('/api/joindin/comments/fetch');
    }

    /**
     * @Then there should be :count ZgPHP meetups in system
     */
    public function thereShouldBeZgphpMeetupsInSystem(int $count)
    {
        Assert::count($this->apiGetJson('/api/joindin/events/'), $count);
    }

    /**
     * @Then there should be :count talks in system
     */
    public function thereShouldBeTalksInSystem(int $count)
    {
        Assert::count($this->apiGetJson('/api/joindin/talks/'), $count);
    }

    /**
     * @Then there should be :count comment in system
     */
    public function thereShouldBeCommentInSystem(int $count)
    {
        $data = $this->apiGetJson('/api/joindin/comments/');

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
