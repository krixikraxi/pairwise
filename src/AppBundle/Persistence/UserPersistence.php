<?php

namespace AppBundle\Persistence;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;

class UserPersistence
{
    private $em;

    public function __construct(ObjectManager $entityManager) {
        $this->em = $entityManager;
    }

    public function persistUser(User $user) {
        //todo: research if this check is enough, throw specific exception
        if($user == null) {
            throw new Exception("User null");
        } else if($user->getPartnerone() == null || $user->getPartnertwo() == null) {
            throw new Exception("Partner null");
        }

        $this->em->persist($user->getPartnerone());
        $this->em->persist($user->getPartnertwo());
        $this->em->persist($user);
        $this->em->flush();
    }
}