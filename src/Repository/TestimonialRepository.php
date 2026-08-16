<?php

namespace App\Repository;

use App\Entity\Testimonial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testimonial>
 */
class TestimonialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testimonial::class);
    }

    /**
     * @return Testimonial[] Returns an array of Testimonial objects
     */
    public function findAllActive(string $orderBy="Desc",int|null $limit=null): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isActive = :val')
            ->setParameter('val', true)
            ->orderBy('t.id', 'Desc')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Testimonial
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
