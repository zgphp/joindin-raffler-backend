<?php

namespace App\Controller;

use App\Repository\JoindInEventRepository;
use App\Service\JoindInClient;
use App\Service\JoindInEventRetrieval;
use Symfony\Component\HttpFoundation\JsonResponse;

class JoindInEventController
{
    /**
     * @var JoindInEventRepository
     */
    private $eventRepository;
    /**
     * @var JoindInClient
     */
    private $joindInClient;

    /**
     * @var JoindInEventRetrieval
     */
    private $joindInEventRetrieval;

    public function __construct(
        JoindInEventRetrieval $joindInEventRetrieval,
        JoindInEventRepository $eventRepository,
        JoindInClient $joindInClient
    ) {
        $this->eventRepository       = $eventRepository;
        $this->joindInClient         = $joindInClient;
        $this->joindInEventRetrieval = $joindInEventRetrieval;
    }

    public function fetch()
    {
        $this->joindInEventRetrieval->fetch();

        return new JsonResponse('OK');
    }

    public function eventList()
    {
        $events = $this->eventRepository->findAllSortedFromNewestToOldest();

        return new JsonResponse($events);
    }
}
