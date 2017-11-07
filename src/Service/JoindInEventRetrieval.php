<?php

namespace App\Service;

use App\Entity\JoindInEvent;
use App\Repository\JoindInEventRepository;
use Doctrine\ORM\EntityManagerInterface;

class JoindInEventRetrieval
{
    /** @var JoindInClient */
    private $joindInClient;
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var JoindInEventRepository */
    private $eventRepository;

    public function __construct(
        JoindInClient $joindInClient,
        EntityManagerInterface $entityManager,
        JoindInEventRepository $eventRepository)
    {
        $this->joindInClient   = $joindInClient;
        $this->entityManager   = $entityManager;
        $this->eventRepository = $eventRepository;
    }

    public function fetch(): void
    {
        foreach ($this->joindInClient->fetchZgPhpEvents() as $eventData) {
            $entity = $this->eventRepository->find($eventData->getId());

            if (null === $entity) {
                $entity = new JoindInEvent($eventData->getId(), $eventData->getName(), $eventData->getStartDate(), $eventData->getEndDate());
                $this->entityManager->persist($entity);
            } else {
                $entity->setName($eventData->getName());
                $entity->setStartDate($eventData->getStartDate());
                $entity->setEndDate($eventData->getEndDate());
            }

            $this->entityManager->flush();
        }
    }
}
