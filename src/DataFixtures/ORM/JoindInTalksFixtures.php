<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use App\Entity\JoindInTalk;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class JoindInTalksFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $octoberMeetup = $this->getReference('event-october');

        $octoberTalks = [
            22817 => new JoindInTalk(22817, 'Fullstacking - 101', $octoberMeetup),
            22818 => new JoindInTalk(22818, 'HTTP caching - symfony, varnish, invalidation, naming',
                $octoberMeetup),
        ];

        foreach ($octoberTalks as $talkID => $talk) {
            $manager->persist($talk);
            $this->addReference('october-talk-'.$talkID, $talk);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 20;
    }
}
