<?php

namespace spec\App\Service;

use App\Entity\JoindInEvent;
use App\Entity\JoindInTalk;
use App\JoindIn\TalkData;
use App\Repository\JoindInTalkRepository;
use App\Service\JoindInClient;
use App\Service\JoindInTalkRetrieval;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JoindInTalkRetrievalSpec extends ObjectBehavior
{
    public function let(
        JoindInClient $joindInClient,
        EntityManager $entityManager,
        JoindInTalkRepository $talkRepository
    ) {
        $this->beConstructedWith($joindInClient, $entityManager, $talkRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(JoindInTalkRetrieval::class);
    }

    public function it_will_add_new_talk_when_fetching_event_talks(
        JoindInClient $joindInClient,
        EntityManager $entityManager,
        JoindInTalkRepository $talkRepository,
        JoindInEvent $event,
        TalkData $talkData
    ) {
        $joindInClient->fetchTalksForEvent($event)
            ->shouldBeCalled()
            ->willReturn([$talkData]);

        $talkData->getId()
            ->shouldBeCalled()
            ->willReturn(2345);
        $talkData->getTitle()
            ->shouldBeCalled()
            ->willReturn('Talk about something');

        $entityManager->persist(Argument::type(JoindInTalk::class))
            ->shouldBeCalled();

        $entityManager->flush()
            ->shouldBeCalled();

        $this->fetch($event);
    }
}
