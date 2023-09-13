<?php

namespace App\Repository;

use App\Entity\ListGift;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListGift>
 *
 * @method ListGift|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListGift|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListGift[]    findAll()
 * @method ListGift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListGiftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListGift::class);
    }

//    /**
//     * @return ListGift[] Returns an array of ListGift objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ListGift
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * Find listGifts where today's date is between openingDate and closingDate.
     *
     * @return ListGift[] Returns an array of ListGift objects
     */
    public function findActiveListGifts(): array
    {
        $today = new \DateTime();

        return $this->createQueryBuilder('l')
            ->andWhere('l.openingDate <= :today')
            ->andWhere('l.closingDate >= :today')
            ->andWhere('l.active = :active')
            ->setParameter('today', $today)
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();
    }
}
