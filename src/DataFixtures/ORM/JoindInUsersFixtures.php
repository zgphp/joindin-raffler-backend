<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use App\Entity\JoindInUser;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class JoindInUsersFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = [
            'user1' => new JoindInUser(45128, 'username1', 'User Named One'),
            'user2' => new JoindInUser(26764, 'username2', 'User Named Two'),
        ];

        foreach ($users as $userRef => $user) {
            $manager->persist($user);
            $this->addReference($userRef, $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 30;
    }
}
