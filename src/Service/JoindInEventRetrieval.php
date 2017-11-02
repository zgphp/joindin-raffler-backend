<?php

namespace App\Service;

use App\Entity\JoindInEvent;
use App\Repository\JoindInEventRepository;
use Doctrine\ORM\EntityManager;

class JoindInEventRetrieval
{
    /** @var JoindInClient */
    private $joindInClient;
    /** @var EntityManager */
    private $entityManager;
    /** @var JoindInEventRepository */
    private $eventRepository;

    public function __construct(
        JoindInClient $joindInClient,
        EntityManager $entityManager,
        JoindInEventRepository $eventRepository)
    {
        $this->joindInClient   = $joindInClient;
        $this->entityManager   = $entityManager;
        $this->eventRepository = $eventRepository;
    }

    public function fetch()
    {
        foreach ($this->joindInClient->fetchZgPhpEvents() as $eventData) {
            $entity = $this->eventRepository->find($eventData->getId());

            if (null === $entity) {
                $entity = new JoindInEvent($eventData->getId(), $eventData->getName());
                $this->entityManager->persist($entity);
            } else {
                $entity->setName($eventData->getName());
            }

            $this->entityManager->flush();
        }
    }
}
