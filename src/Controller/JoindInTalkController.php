<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\JoindInEventRepository;
use App\Repository\JoindInTalkRepository;
use App\Service\JoindInTalkRetrieval;
use Symfony\Component\HttpFoundation\JsonResponse;

class JoindInTalkController
{
    /** @var JoindInTalkRetrieval */
    private $joindInTalkRetrieval;
    /** @var JoindInEventRepository */
    private $eventRepository;
    /** @var JoindInTalkRepository */
    private $talkRepository;

    public function __construct(
        JoindInTalkRetrieval $joindInTalkRetrieval,
        JoindInEventRepository $eventRepository,
        JoindInTalkRepository $talkRepository
    ) {
        $this->joindInTalkRetrieval = $joindInTalkRetrieval;
        $this->eventRepository      = $eventRepository;
        $this->talkRepository       = $talkRepository;
    }

    public function fetch(): JsonResponse
    {
        foreach ($this->eventRepository->findAll() as $event) {
            $this->joindInTalkRetrieval->fetch($event);
        }

        return new JsonResponse('OK');
    }

    public function talkList(): JsonResponse
    {
        $talks = $this->talkRepository->findAll();

        return new JsonResponse($talks);
    }
}
