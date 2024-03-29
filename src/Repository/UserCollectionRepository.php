<?php

namespace App\Repository;

use App\Entity\UserCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<UserCollection>
 *
 * @method UserCollection|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCollection|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCollection[]    findAll()
 * @method UserCollection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCollection::class);
    }

    /**
     * @return UserCollection[] Returns an array of UserCollection objects
     */
    public function collectionsWithProduct($limit = null): array
    {
        $query = $this->createQueryBuilder('u')
            ->select('u.id', 'u.name', 'COUNT(p) as productCount')
            ->leftJoin('u.product', 'p')
            ->groupBy('u.id')
            ->orderBy('u.id', 'ASC');

        if ($limit !== null) {
            $query->setMaxResults($limit);
        }

        return $query->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }


    //    /**
    //     * @return UserCollection[] Returns an array of UserCollection objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserCollection
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
