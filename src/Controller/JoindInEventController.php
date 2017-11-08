<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\JoindInEventRepository;
use App\Service\JoindInEventRetrieval;
use Symfony\Component\HttpFoundation\JsonResponse;

class JoindInEventController
{
    /**
     * @var JoindInEventRepository
     */
    private $eventRepository;

    /**
     * @var JoindInEventRetrieval
     */
    private $joindInEventRetrieval;

    public function __construct(
        JoindInEventRetrieval $joindInEventRetrieval,
        JoindInEventRepository $eventRepository
    ) {
        $this->eventRepository       = $eventRepository;
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
