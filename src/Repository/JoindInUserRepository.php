<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\JoindInUser;
use Doctrine\ORM\EntityRepository;

class JoindInUserRepository extends EntityRepository
{
    public function findOneByUsername(string $username): ?JoindInUser
    {
        return parent::findOneByUsername($username);
    }
}
