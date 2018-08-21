<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

    /**
     * @Given I am not logged in
     */
    public function iAmNotLoggedIn()
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof BrowserKitDriver) {
            throw new UnsupportedDriverActionException('This step is only supported by the BrowserKitDriver', $driver);
        }

        /** @var \Symfony\Bundle\FrameworkBundle\Client $client */
        $client = $driver->getClient();

        $client->getCookieJar()->set(new Cookie(session_name(), 'anon_user_not_logged_in'));

        /** @var Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->getService('session');

        $providerKey = $this->kernel->getContainer()->getParameter('fos_user.firewall_name');

        $token = new AnonymousToken('$3cr37', 'anon', []);
        $session->set('_security_'.$providerKey, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    /**
     * @Given I am authorized with :accessRole
     */
    public function iAmAuthorizedWith($accessRole)
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof BrowserKitDriver) {
            throw new UnsupportedDriverActionException('This step is only supported by the BrowserKitDriver', $driver);
        }

        /** @var \Symfony\Bundle\FrameworkBundle\Client $client */
        $client = $driver->getClient();

        /** @var Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->getService('session');

//        clear residual session data from any previous scenarios
        $session->clear();

        $providerKey = $this->kernel->getContainer()->getParameter('fos_user.firewall_name');

        /** @var \FOS\UserBundle\Doctrine\UserManager $fosUserManager */
        $fosUserManager = $this->getService('fos_user.user_manager');

        $user = $fosUserManager->findUserByUsername('admin');

        if (null !== $user) {
            $token = new UsernamePasswordToken($user, $user->getPassword(), $providerKey, [$accessRole]);

            $session->set('_security_'.$providerKey, serialize($token));
            $session->save();

            $cookie = new Cookie($session->getName(), $session->getId());
            $client->getCookieJar()->set($cookie);
        }
    }
}
