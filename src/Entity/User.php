<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool
     *
     * Flags this user as admin (adds ROLE_SUPER_ADMIN)
     */
    private $administrator;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function isAdministrator(): bool
    {
        return $this->isSuperAdmin();
    }

    public function setAdministrator($boolean): void
    {
        $this->setSuperAdmin($boolean);
        $this->administrator = $boolean;
    }
}
