<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\HttpKernel\KernelInterface;

class FOSWebContext extends MinkContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When I visit :url
     */
    public function iVisit(string $url)
    {
        $this->visit($url);
    }

    /**
     * @Given there is no user with username :username
     */
    public function thereIsNoUserWithUsername($username)
    {
        $userManager = $this->getService('fos_user.user_manager');
        $user        = $userManager->findUserByUsername($username);
        if (null !== $user) {
            $userManager->deleteUser($user);
        }
    }

    protected function getService(string $name)
    {
        return $this->kernel->getContainer()->get($name);
    }
}
