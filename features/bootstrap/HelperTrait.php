<?php

declare(strict_types=1);

use App\Entity\JoindInUser;
use App\Entity\Raffle;
use App\Repository\JoindInCommentRepository;
use App\Repository\JoindInEventRepository;
use App\Repository\JoindInTalkRepository;
use App\Repository\JoindInUserRepository;
use App\Repository\RaffleRepository;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\ORM\EntityManager;

trait HelperTrait
{
    /**
     * @BeforeScenario
     */
    public function cleanDB(BeforeScenarioScope $scope): void
    {
        // Remove all meetups, talks & comments.
        foreach ($this->getEventRepository()->findAll() as $joindInEvent) {
            $this->getEntityManager()->remove($joindInEvent);
        }

        // Remove all raffles.
        foreach ($this->getRaffleRepository()->findAll() as $raffle) {
            $this->getEntityManager()->remove($raffle);
        }

        // Remove all users.
        foreach ($this->getUserRepository()->findAll() as $user) {
            $this->getEntityManager()->remove($user);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @Transform :user
     */
    public function castToUser(string $username): JoindInUser
    {
        $user = $this->getUserRepository()->findOneByUsername($username);

        if (null === $user) {
            throw new \RuntimeException('Cant find user with username: '.$username);
        }

        return $user;
    }

    protected function loadMultipleUsers(string $userNames): array
    {
        $users = [];

        foreach (explode(',', $userNames) as $userName) {
            $users[] = $this->getUserRepository()->findOneByUsername($userName);
        }

        return $users;
    }

    protected function getEntityManager(): EntityManager
    {
        return $this->getService('doctrine.orm.entity_manager');
    }

    protected function loadRaffle(string $raffleId): Raffle
    {
        return $this->getRaffleRepository()->find($raffleId);
    }

    protected function getEventRepository(): JoindInEventRepository
    {
        return $this->getService(JoindInEventRepository::class);
    }

    protected function getTalkRepository(): JoindInTalkRepository
    {
        return $this->getService(JoindInTalkRepository::class);
    }

    protected function getCommentRepository(): JoindInCommentRepository
    {
        return $this->getService(JoindInCommentRepository::class);
    }

    protected function getUserRepository(): JoindInUserRepository
    {
        return $this->getService(JoindInUserRepository::class);
    }

    protected function getRaffleRepository(): RaffleRepository
    {
        return $this->getService(RaffleRepository::class);
    }
}
