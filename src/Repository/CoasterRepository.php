<?php

namespace App\Repository;

use App\Entity\Coaster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Coaster>
 */
class CoasterRepository extends ServiceEntityRepository
{

    public function findFiltered(
        string $parkId = '',
        string $categoryId = '',
        int $page = 1,
        int $count = 25,
        string $search = ''
    ): Paginator
    {

        $begin = ($page - 1) * $count;
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.Park', 'p')
            ->leftJoin('c.Categories', 'cat')
            ->setMaxResults($count)
            ->setFirstResult($begin);

        if ($parkId !== '') {
            $qb->andWhere('p.id = :parkId')
                ->setParameter('parkId', (int)$parkId)
            ;

        }
        if($categoryId !== '') {
            $qb->andWhere('cat.id = :categoryId')
                ->setParameter('categoryId', (int)$categoryId)
            ;
        }
        if(strlen($search) > 2) {
            $qb->andWhere($qb->expr()->like('c.name', ':search'))
                ->setParameter('search', "%$search%");
        }
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            $qb->andWhere('c.published = true OR c.author = :author')
                ->setParameter('author', $this->security->getUser())
            ;
        }

        // Filtrer la categorie
        return new Paginator($qb->getQuery());
    }

    public function __construct(ManagerRegistry $registry, private readonly Security $security
    )
    {
        parent::__construct($registry, Coaster::class);
    }

    //    /**
    //     * @return Coaster[] Returns an array of Coaster objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Coaster
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
