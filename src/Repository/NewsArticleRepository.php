<?php

namespace App\Repository;

use App\Entity\NewsArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewsArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsArticle[]    findAll()
 * @method NewsArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsArticle::class);
    }

    public function findByQuery(array $orderBy, ?string $query = ''): array
    {
        return $this->createQueryBuilder('news')
            ->where('news.title LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('news.' . key($orderBy), $orderBy[key($orderBy)])
            ->getQuery()
            ->getResult()
        ;
    }
}
