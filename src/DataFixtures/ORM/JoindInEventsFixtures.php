<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use App\Entity\JoindInEvent;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class JoindInEventsFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $list = [
            'october'   => new JoindInEvent(6674, 'ZgPHP 2017/10', new DateTime('2017-10-19')),
            'september' => new JoindInEvent(6624, 'ZgPHP 2017/09', new DateTime('2017-09-14')),
            'june'      => new JoindInEvent(6490, 'ZgPHP 2017/06', new DateTime('2017-06-08')),
            'may'       => new JoindInEvent(6357, 'ZgPHP 2017/05', new DateTime('2017-05-18')),
            'april'     => new JoindInEvent(6356, 'ZgpHP 2017/04', new DateTime('2017-04-20')),
            'march'     => new JoindInEvent(6265, 'ZgPHP 2017/03', new DateTime('2017-03-16')),
            'february'  => new JoindInEvent(6264, 'ZgPHP 2017/02', new DateTime('2017-02-16')),
            'january'   => new JoindInEvent(6263, 'ZgPHP 2017-01', new DateTime('2017-01-19')),
        ];

        foreach ($list as $month => $event) {
            $manager->persist($event);
            $this->addReference('event-'.$month, $event);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder(): int
    {
        return 10;
    }
}
