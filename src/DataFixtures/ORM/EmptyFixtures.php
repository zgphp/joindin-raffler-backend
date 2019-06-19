<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EmptyFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        //$obj = new \stdClass();
        //$manager->persist($obj);

        $manager->flush();
        //$this->addReference('obj', $obj);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder(): int
    {
        return 10;
    }
}
