<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class JoindInUserRepository extends EntityRepository
{
    public function findOneByUsername(string $username)
    {
        return parent::findOneByUsername($username);
    }
}
