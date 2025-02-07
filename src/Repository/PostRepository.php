<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param mixed $value
     * @return Post[] Returns an array of Post objects
     */
    public function findByCustomField($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.customField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param mixed $value
     * @return Post|null Returns a Post object or null
     */
    public function findOneByCustomField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.customField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
