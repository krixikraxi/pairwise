<?php
namespace AppBundle\Persistence;

use AppBundle\Entity\Bill;
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

    /**
     * Find an not billed Bill with a given ID and User.
     *
     * @param User $user
     * @param int $billId
     * @return mixed
     */
    public function findBillByIdFromUserNotBilled(User $user, int $billId) {
        $query = $this->createQueryBuilder('b')
            ->where('(b.partner = :p1 OR b.partner = :p2) AND b.id = :id AND b.billed = false')
            ->setParameter('p1', $user->getPartnerone()->getId())
            ->setParameter('p2', $user->getPartnertwo()->getId())
            ->setParameter('id', $billId)
            ->getQuery();

        //todo in php 7.1 there will be a return object or null typehint
        return $query->getOneOrNullResult();
    }
}