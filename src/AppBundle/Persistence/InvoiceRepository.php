<?php
namespace AppBundle\Persistence;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class InvoiceRepository extends EntityRepository
{
    /**
     * Find all Invoices from a given User.
     *
     * @param User $user
     * @return array
     */
    public function findAllInvoicesFromTheUser(User $user) {
        $query = $this->createQueryBuilder('b')
            ->where('b.user = :user')
            ->setParameter('user', $user->getId())
            ->orderBy('b.invoicedate', 'ASC')
            ->getQuery();

        return $query->getResult();
    }
}