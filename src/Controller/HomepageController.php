<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\JoindInEventRepository;
use App\Repository\JoindInTalkRepository;
use App\Service\JoindInCommentRetrieval;
use App\Service\JoindInEventRetrieval;
use App\Service\JoindInTalkRetrieval;
use Symfony\Component\HttpFoundation\Response;

class HomepageController
{
    /**
     * @var \App\Service\JoindInEventRetrieval
     */
    private $joindInEventRetrieval;
    /**
     * @var \App\Repository\JoindInEventRepository
     */
    private $eventRepository;
    /**
     * @var \App\Service\JoindInTalkRetrieval
     */
    private $joindInTalkRetrieval;
    /**
     * @var \App\Repository\JoindInTalkRepository
     */
    private $talkRepository;
    /**
     * @var \App\Service\JoindInCommentRetrieval
     */
    private $joindInCommentRetrieval;

    public function __construct(
        JoindInEventRetrieval $joindInEventRetrieval,
        JoindInEventRepository $eventRepository,
        JoindInTalkRetrieval $joindInTalkRetrieval,
        JoindInTalkRepository $talkRepository,
        JoindInCommentRetrieval $joindInCommentRetrieval
    ) {
        $this->joindInEventRetrieval   = $joindInEventRetrieval;
        $this->eventRepository         = $eventRepository;
        $this->joindInTalkRetrieval    = $joindInTalkRetrieval;
        $this->talkRepository          = $talkRepository;
        $this->joindInCommentRetrieval = $joindInCommentRetrieval;
    }

    public function index(): Response
    {
        return new Response('OK');
    }

    public function fetchJoindInData()
    {
        try {
            $this->joindInEventRetrieval->fetch();
            foreach ($this->eventRepository->findAll() as $event) {
                $this->joindInTalkRetrieval->fetch($event);
            }
            foreach ($this->talkRepository->findAll() as $talk) {
                $this->joindInCommentRetrieval->fetch($talk);
            }

            return new Response('OK');
        } catch (\Exception $ex) {
            return new Response($ex->getMessage(), $ex->getCode());
        }
    }
}
