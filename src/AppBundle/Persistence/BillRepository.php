<?php
namespace AppBundle\Persistence;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class BillRepository extends EntityRepository
{

    /**
     * Find all Bills from a given User.
     *
     * @param User $user
     * @return array
     */
    public function findAllBillsFromTheUser(User $user) {
        $query = $this->createQueryBuilder('b')
            ->where('b.partner = :p1 OR b.partner = :p2')
            ->setParameter('p1', $user->getPartnerone()->getId())
            ->setParameter('p2', $user->getPartnertwo()->getId())
            ->orderBy('b.billdate', 'ASC')
            ->getQuery();

        return $query->getResult();
    }
}