<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\JoindInUser;
use App\Entity\Raffle;
use App\Repository\JoindInEventRepository;
use App\Repository\JoindInUserRepository;
use App\Repository\RaffleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RaffleWebController extends Controller
{
    /** @var JoindInEventRepository */
    /** @var RaffleRepository */
    private $raffleRepository;
    /** @var JoindInUserRepository */
    private $userRepository;
    private $eventRepository;
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        JoindInEventRepository $eventRepository,
        RaffleRepository $raffleRepository,
        JoindInUserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->eventRepository  = $eventRepository;
        $this->raffleRepository = $raffleRepository;
        $this->userRepository   = $userRepository;
        $this->entityManager    = $entityManager;
    }

    /*

            dev.raffler.loc:8000/api/joindin/events/fetch
            dev.raffler.loc:8000/api/joindin/talks/fetch
            dev.raffler.loc:8000/api/joindin/comments/fetch
    */

    public function index()
    {
        $data = [
            'events' => $this->getAllEvents(),
        ];

        return $this->render('Raffle/index.html.twig', $data);
    }

    public function start(Request $request)
    {
        $requestVars = $request->request->all();

        $eventIds = array_keys($requestVars);

        $id = Uuid::uuid4()->toString();

        $events = new ArrayCollection();

        foreach ($eventIds as $eventId) {
            $event = $this->eventRepository->find($eventId);
            $events->add($event);
        }

        $raffle = new Raffle($id, $events);

        $this->entityManager->persist($raffle);

        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('raffle_web_show', ['id' => $raffle->getId()]));
    }

    public function show(string $id)
    {
        $raffle = $this->loadRaffle($id);

        $data = [
            'raffle' => $raffle,
        ];

        return $this->render('Raffle/show.html.twig', $data);
    }

    public function pick(string $id)
    {
        $raffle = $this->loadRaffle($id);

        $user = $raffle->pick();

        $data = [
            'raffle' => $raffle,
            'user'   => $user,
        ];

        return $this->render('Raffle/pick.html.twig', $data);
    }

    public function winner(string $id, string $userId)
    {
        $raffle = $this->loadRaffle($id);
        $user   = $this->loadUser($userId);

        $raffle->userWon($user);
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('raffle_web_show', ['id' => $raffle->getId()]));
    }

    public function noShow(string $id, string $userId)
    {
        $raffle = $this->loadRaffle($id);
        $user   = $this->loadUser($userId);

        $raffle->userIsNoShow($user);
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('raffle_web_show', ['id' => $raffle->getId()]));
    }

    private function getAllEvents()
    {
        return $this->eventRepository->findAll();
    }

    private function loadRaffle(string $raffleId): Raffle
    {
        return $this->raffleRepository->find($raffleId);
    }

    private function loadUser(string $id): JoindInUser
    {
        return $this->userRepository->find($id);
    }
}
