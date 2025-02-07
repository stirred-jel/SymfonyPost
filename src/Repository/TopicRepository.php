<?php

namespace App\Repository;

use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Topic>
 */
class TopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    /**
     * @param mixed $value
     * @return Topic[] Returns an array of Topic objects
     */
    public function findByCustomField($value): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.customField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param mixed $value
     * @return Topic|null Returns a Topic object or null
     */
    public function findOneByCustomField($value): ?Topic
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.customField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
