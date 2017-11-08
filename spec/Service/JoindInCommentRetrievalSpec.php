<?php

declare(strict_types=1);

namespace spec\App\Service;

use App\Entity\JoindInComment;
use App\Entity\JoindInTalk;
use App\Entity\JoindInUser;
use App\JoindIn\CommentData;
use App\Repository\JoindInCommentRepository;
use App\Repository\JoindInUserRepository;
use App\Service\JoindInClient;
use App\Service\JoindInCommentRetrieval;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JoindInCommentRetrievalSpec extends ObjectBehavior
{
    public function let(
        JoindInClient $joindInClient,
        EntityManager $entityManager,
        JoindInCommentRepository $commentRepository,
        JoindInUserRepository $userRepository
    ) {
        $this->beConstructedWith($joindInClient, $entityManager, $commentRepository, $userRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(JoindInCommentRetrieval::class);
    }

    public function it_will_add_new_comment_and_user_when_fetching_talk_comments(
        JoindInTalk $joindInTalk,
        JoindInClient $joindInClient,
        EntityManager $entityManager,
        JoindInCommentRepository $commentRepository,
        JoindInUserRepository $userRepository,
        CommentData $commentData
    ) {
        $commentData->getId()->shouldBeCalled()->willReturn(34567);
        $commentData->getUserId()->shouldBeCalled()->willReturn(12);
        $commentData->getComment()->shouldBeCalled()->willReturn('Great talk');
        $commentData->getRating()->shouldBeCalled()->willReturn(5);

        $commentData->getUserName()->shouldBeCalled()->willReturn('user_name');
        $commentData->getUserDisplayName()->shouldBeCalled()->willReturn('User Name');

        $joindInClient->fetchCommentsForTalk($joindInTalk)
            ->shouldBeCalled()
            ->willReturn([$commentData]);

        $userRepository->find(12)
            ->shouldBeCalled()
            ->willReturn(null);

        $commentRepository->find(34567)
            ->shouldBeCalled()
            ->willReturn(null);

        $entityManager->persist(Argument::type(JoindInUser::class))
            ->shouldBeCalled();

        $entityManager->persist(Argument::type(JoindInComment::class))
            ->shouldBeCalled();

        $entityManager->flush()
            ->shouldBeCalled();

        $this->fetch($joindInTalk);
    }

    public function it_will_update_comment_and_user_when_fetching_talk_comments(
        JoindInTalk $joindInTalk,
        JoindInClient $joindInClient,
        EntityManager $entityManager,
        JoindInCommentRepository $commentRepository,
        JoindInUserRepository $userRepository,
        CommentData $commentData,
        JoindInUser $user,
        JoindInComment $comment
    ) {
        $commentData->getId()->shouldBeCalled()->willReturn(34567);
        $commentData->getUserId()->shouldBeCalled()->willReturn(12);
        $commentData->getComment()->shouldBeCalled()->willReturn('Great talk');
        $commentData->getRating()->shouldBeCalled()->willReturn(5);

        $commentData->getUserName()->shouldBeCalled()->willReturn('user_name');
        $commentData->getUserDisplayName()->shouldBeCalled()->willReturn('User Name');

        $joindInClient->fetchCommentsForTalk($joindInTalk)
            ->shouldBeCalled()
            ->willReturn([$commentData]);

        $userRepository->find(12)
            ->shouldBeCalled()
            ->willReturn($user);

        $commentRepository->find(34567)
            ->shouldBeCalled()
            ->willReturn($comment);

        $user->setUsername('user_name')->shouldBeCalled();
        $user->setDisplayName('User Name')->shouldBeCalled();

        $comment->setComment('Great talk')->shouldBeCalled();
        $comment->setRating(5)->shouldBeCalled();
        $comment->setUser($user)->shouldBeCalled();
        $comment->setTalk($joindInTalk)->shouldBeCalled();

        $entityManager->flush()
            ->shouldBeCalled();

        $this->fetch($joindInTalk);
    }
}
