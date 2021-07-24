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
}
