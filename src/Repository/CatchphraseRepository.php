<?php

namespace App\Repository;

use App\Entity\Catchphrase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Catchphrase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Catchphrase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Catchphrase[]    findAll()
 * @method Catchphrase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatchphraseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Catchphrase::class);
    }
}
