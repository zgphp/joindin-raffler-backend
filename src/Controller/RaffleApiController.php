<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\JoindInUser;
use App\Entity\Raffle;
use App\Exception\NoCommentsToRaffleException;
use App\Repository\JoindInEventRepository;
use App\Repository\JoindInUserRepository;
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
    /** @var JoindInUserRepository */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        JoindInEventRepository $eventRepository,
        RaffleRepository $raffleRepository,
        JoindInUserRepository $userRepository
    ) {
        $this->entityManager    = $entityManager;
        $this->eventRepository  = $eventRepository;
        $this->raffleRepository = $raffleRepository;
        $this->userRepository   = $userRepository;
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

    public function pick(string $id)
    {
        $raffle = $this->loadRaffle($id);

        try {
            $user = $raffle->pick();
        } catch (NoCommentsToRaffleException $exception) {
            return new JsonResponse(['error' => true, 'message' => $exception->getMessage()]);
        }

        return new JsonResponse($user);
    }

    public function winner(string $id, string $userId)
    {
        $raffle = $this->loadRaffle($id);
        $user   = $this->loadUser($userId);

        $raffle->userWon($user);
        $this->entityManager->flush();

        return new JsonResponse('OK');
    }

    public function noShow(string $id, string $userId)
    {
        $raffle = $this->loadRaffle($id);
        $user   = $this->loadUser($userId);

        $raffle->userIsNoShow($user);
        $this->entityManager->flush();

        return new JsonResponse('OK');
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

    private function loadUser(string $id): JoindInUser
    {
        return $this->userRepository->find($id);
    }
}
