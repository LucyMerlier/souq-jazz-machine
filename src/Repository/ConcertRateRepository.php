<?php

namespace App\Repository;

use App\Entity\ConcertRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConcertRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConcertRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConcertRate[]    findAll()
 * @method ConcertRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConcertRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConcertRate::class);
    }
}
