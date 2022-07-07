<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 *
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * @return Query
     */
    public function findAllVisibleQuery(PropertySearch $search): Query
    {
        $query = $this->findVisibleQuery();

        if($search->getMaxPrice()){
            $query = $query
            ->where('p.prix <= :maxPrice')
            ->setParameter('maxprice', $search->getMaxPrice());
        }

        if($search->getMinPrice()){
            $query = $query
            ->where('p.prix <= :minPrice')
            ->setParameter('minprice', $search->getMinPrice());
        }

        return $query->getQuery();
    }

    public function add(Property $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Property $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatest(): array 
    {
        return $this->findVisibleQuery()
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }
//    /**
//     * @return Property[] Returns an array of Property objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Property
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }




/**
 * Returns number of "Property" per day
 * @return void
 * 
 */

    public function countByDate(){
        $query = $this->createQueryBuilder('a')
        ->select('SUBSTRING(a.dateAchat,1,10) as date_achat, COUNT(a) as count')
        ->groupBy('date_achat');

        return $query->getQuery()->getResult();

    }

}
