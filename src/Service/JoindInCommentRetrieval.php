<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\JoindInComment;
use App\Entity\JoindInTalk;
use App\Entity\JoindInUser;
use App\Repository\JoindInCommentRepository;
use App\Repository\JoindInUserRepository;
use Doctrine\ORM\EntityManagerInterface;

class JoindInCommentRetrieval
{
    /** @var JoindInClient */
    private $joindInClient;
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var JoindInCommentRepository */
    private $commentRepository;
    /** @var JoindInUserRepository */
    private $userRepository;

    public function __construct(
        JoindInClient $joindInClient,
        EntityManagerInterface $entityManager,
        JoindInCommentRepository $commentRepository,
        JoindInUserRepository $userRepository
    ) {
        $this->joindInClient     = $joindInClient;
        $this->entityManager     = $entityManager;
        $this->commentRepository = $commentRepository;
        $this->userRepository    = $userRepository;
    }

    public function fetch(JoindInTalk $talk): void
    {
        $comments = $this->joindInClient->fetchCommentsForTalk($talk);

        foreach ($comments as $commentData) {
            $user = $this->userRepository->find($commentData->getUserId());

            $comment = $this->commentRepository->find($commentData->getId());

            if (null === $user) {
                $user = new JoindInUser($commentData->getUserId(), $commentData->getUserName(), $commentData->getUserDisplayName());
                $this->entityManager->persist($user);
            } else {
                $user->setUsername($commentData->getUserName());
                $user->setDisplayName($commentData->getUserDisplayName());
            }

            if (null === $comment) {
                $comment = new JoindInComment($commentData->getId(), $commentData->getComment(), $commentData->getRating(), $user, $talk);
                $this->entityManager->persist($comment);
            } else {
                $comment->setComment($commentData->getComment());
                $comment->setRating($commentData->getRating());
                $comment->setUser($user);
                $comment->setTalk($talk);
            }

            $this->entityManager->flush();
        }
    }
}
