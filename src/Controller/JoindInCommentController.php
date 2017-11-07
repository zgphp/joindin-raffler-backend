<?php

namespace App\Controller;

use App\Repository\JoindInCommentRepository;
use App\Repository\JoindInTalkRepository;
use App\Service\JoindInCommentRetrieval;
use Symfony\Component\HttpFoundation\JsonResponse;

class JoindInCommentController
{
    /** @var JoindInCommentRetrieval */
    private $joindInCommentRetrieval;
    /** @var JoindInCommentRepository */
    private $joindInCommentRepository;
    /** @var JoindInTalkRepository */
    private $talkRepository;

    public function __construct(
        JoindInCommentRetrieval $joindInCommentRetrieval,
        JoindInCommentRepository $joindInCommentRepository,
        JoindInTalkRepository $talkRepository
    ) {
        $this->joindInCommentRetrieval  = $joindInCommentRetrieval;
        $this->joindInCommentRepository = $joindInCommentRepository;
        $this->talkRepository           = $talkRepository;
    }

    public function fetch(): JsonResponse
    {
        foreach ($this->talkRepository->findAll() as $talk) {
            $this->joindInCommentRetrieval->fetch($talk);
        }

        return new JsonResponse('OK');
    }

    public function commentList(): JsonResponse
    {
        $comments = $this->joindInCommentRepository->findAll();

        return new JsonResponse($comments);
    }
}
