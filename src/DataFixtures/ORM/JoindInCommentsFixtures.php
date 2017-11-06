<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use App\Entity\JoindInComment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class JoindInCommentsFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $fullStacking101Talk = $this->getReference('october-talk-22817');

        $fullStackingComments = [
            'comment1' => new JoindInComment(
                83645,
                'A nice overview of all the pros and cons of knowing a lot of stuff and how to learn
                 a new technology and then use it in your daily routines',
                5,
                $this->getReference('user2'),
                $fullStacking101Talk
            ),
        ];

        $httpCachingTalk = $this->getReference('october-talk-22818');

        $httpCachingComments = [
            'comment1' => new JoindInComment(
                83555,
                'Great talk, some very useful Symfony HTTP cache tips and how-to were mentioned.',
                5,
                $this->getReference('user1'),
                $httpCachingTalk
            ),
            'comment2' => new JoindInComment(
                83646,
                'Great insight on how to use and implement http caching on a huge project.
                 Really liked the small details and real life examples that happen in the real world :)',
                5,
                $this->getReference('user2'),
                $httpCachingTalk
            ),
        ];

        foreach ($fullStackingComments as $commentRef => $fullStackingComment) {
            $manager->persist($fullStackingComment);
            $this->addReference('fullStacking-'.$commentRef, $fullStackingComment);
        }

        foreach ($httpCachingComments as $commentRef => $httpCachingComment) {
            $manager->persist($httpCachingComment);
            $this->addReference('httpCaching-'.$commentRef, $httpCachingComment);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 40;
    }
}
