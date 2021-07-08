<?php

namespace App\Repository;

use App\Entity\MusicSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MusicSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method MusicSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method MusicSheet[]    findAll()
 * @method MusicSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MusicSheet::class);
    }

    // /**
    //  * @return MusicSheet[] Returns an array of MusicSheet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MusicSheet
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
