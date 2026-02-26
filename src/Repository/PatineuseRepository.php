<?php

namespace App\Repository;

use App\Entity\Patineuse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patineuse>
 */
class PatineuseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patineuse::class);
    }

    /**
     * @param int[] $annees
     * @return int
     */
    public function countByAnnees(array $annees): int
    {
        if (empty($annees)) {
            return 0;
        }

        return (int) $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.anneeDeNaissance IN (:annees)')
            ->setParameter('annees', $annees)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Patineuse[] Returns an array of Patineuse objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Patineuse
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
