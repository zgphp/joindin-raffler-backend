<?php

declare(strict_types=1);

namespace spec\App\Service;

use App\Entity\JoindInEvent;
use App\JoindIn\EventData;
use App\Repository\JoindInEventRepository;
use App\Service\JoindInClient;
use App\Service\JoindInEventRetrieval;
use DateTime;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JoindInEventRetrievalSpec extends ObjectBehavior
{
    public function let(
        JoindInClient $joindInClient,
        EntityManager $entityManager,
        JoindInEventRepository $eventRepository
    ) {
        $this->beConstructedWith($joindInClient, $entityManager, $eventRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(JoindInEventRetrieval::class);
    }

    public function it_will_add_new_event_to_our_system(
        JoindInClient $joindInClient,
        EntityManager $entityManager,
        JoindInEventRepository $eventRepository,
        EventData $eventData,
        DateTime $date
    ) {
        $joindInClient->fetchZgPhpEvents()
            ->shouldBeCalled()
            ->willReturn([$eventData]);

        $eventData->getId()
            ->shouldBeCalled()
            ->willReturn(123);

        $eventData->getName()
            ->shouldBeCalled()
            ->willReturn('ZgPHP meetup #xx');

        $eventData->getDate()
            ->shouldBeCalled()
            ->willReturn($date);

        $eventRepository->find(123)
            ->shouldBeCalled()
            ->willReturn(null);

        $entityManager->persist(Argument::type(JoindInEvent::class))
            ->shouldBeCalled();

        $entityManager->flush()
            ->shouldBeCalled();

        $this->fetch();
    }

    public function it_will_update_title_as_event_already_in_system(
        JoindInClient $joindInClient,
        EntityManager $entityManager,
        JoindInEventRepository $eventRepository,
        EventData $eventData,
        JoindInEvent $storedJoindInEvent,
        DateTime $date
    ) {
        $joindInClient->fetchZgPhpEvents()
            ->shouldBeCalled()
            ->willReturn([$eventData]);

        $eventData->getId()
            ->shouldBeCalled()
            ->willReturn(123);

        $eventData->getName()
            ->shouldBeCalled()
            ->willReturn('ZgPHP meetup #xx');

        $eventData->getDate()
            ->shouldBeCalled()
            ->willReturn($date);

        $eventRepository->find(123)
            ->shouldBeCalled()
            ->willReturn($storedJoindInEvent);

        $storedJoindInEvent->setName('ZgPHP meetup #xx')
            ->shouldBeCalled();

        $storedJoindInEvent->setDate($date)
            ->shouldBeCalled();

        $entityManager->flush()
            ->shouldBeCalled();

        $this->fetch();
    }
}
