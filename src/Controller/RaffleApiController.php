<?php

namespace App\Controller;

use App\Entity\Raffle;
use App\Repository\JoindInEventRepository;
use App\Repository\RaffleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RaffleApiController
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var JoindInEventRepository */
    private $eventRepository;
    /** @var RaffleRepository */
    private $raffleRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        JoindInEventRepository $eventRepository,
        RaffleRepository $raffleRepository
    ) {
        $this->entityManager    = $entityManager;
        $this->eventRepository  = $eventRepository;
        $this->raffleRepository = $raffleRepository;
    }

    public function start(Request $request)
    {
        $eventIds = json_decode($request->getContent(), true)['events'];

        $id = Uuid::uuid4()->toString();

        $events = new ArrayCollection();

        foreach ($eventIds as $eventId) {
            $event = $this->eventRepository->find($eventId);
            $events->add($event);
        }

        $raffle = new Raffle($id, $events);

        $this->entityManager->persist($raffle);

        $this->entityManager->flush();

        return new JsonResponse($raffle->getId());
    }

    public function show(string $id)
    {
        $raffle = $this->loadRaffle($id);

        return new JsonResponse($raffle);
    }

    public function comments(string $id)
    {
        $raffle = $this->loadRaffle($id);

        $comments = [];

        foreach ($raffle->getCommentsEligibleForRaffling() as $comment) {
            $comments[] = $comment->jsonSerialize();
        }

        return new JsonResponse($comments);
    }

    private function loadRaffle(string $id): Raffle
    {
        return $this->raffleRepository->find($id);
    }
}
