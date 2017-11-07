<?php

namespace App\Service;

use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\Repository\JoindInTalkRepository;
use Doctrine\ORM\EntityManagerInterface;

class JoindInTalkRetrieval
{
    /** @var JoindInClient */
    private $joindInClient;
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var JoindInTalkRepository */
    private $talkRepository;

    public function __construct(
        JoindInClient $joindInClient,
        EntityManagerInterface $entityManager,
        JoindInTalkRepository $talkRepository
    ) {
        $this->joindInClient  = $joindInClient;
        $this->entityManager  = $entityManager;
        $this->talkRepository = $talkRepository;
    }

    public function fetch(JoindInEvent $event): void
    {
        foreach ($this->joindInClient->fetchTalksForEvent($event) as $talkData) {
            $entity = $this->talkRepository->find($talkData->getId());

            if (null === $entity) {
                $entity = new JoindInTalk($talkData->getId(), $talkData->getTitle(), $event);
                $this->entityManager->persist($entity);
            } else {
                $entity->setTitle($talkData->getTitle());
                $entity->setEvent($event);
            }

            $this->entityManager->flush();
        }
    }
}
