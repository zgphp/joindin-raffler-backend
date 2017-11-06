<?php

use App\Entity\JoindInUser;
use App\Entity\Raffle;
use App\Repository\JoindInCommentRepository;
use App\Repository\JoindInEventRepository;
use App\Repository\JoindInTalkRepository;
use App\Repository\JoindInUserRepository;
use App\Repository\RaffleRepository;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;

abstract class BaseContext implements Context
{
    /**
     * @Transform :user
     */
    public function castToUser(string $username): JoindInUser
    {
        return $this->getUserRepository()->findOneByUsername($username);
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

    abstract protected function getService(string $name);
}
