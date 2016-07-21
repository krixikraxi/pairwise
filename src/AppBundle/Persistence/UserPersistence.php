<?php

namespace AppBundle\Persistence;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserPersistence
{
    private $entityManager ;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function persistUser(User $user) {


        //$em->persist($task);
        //$em->flush();

    }

}