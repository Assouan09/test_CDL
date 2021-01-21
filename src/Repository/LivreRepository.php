<?php

namespace App\Repository;

use App\Entity\Livre;
use App\Classe\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    /**
     * Requete recherche livre
     * @return livre filtrer
     */
    public function findWithSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('l')
            ->select('c','a','l')
            ->join('l.category' , 'c')
            ->join('l.auteur', 'a');

            if(!empty($search->categories)){
                $query = $query
                    ->andWhere('c.id IN (:categories)')
                    ->setParameter('categories', $search->categories);
            }

            if(!empty($search->auteur)){
                $query = $query
                    ->andWhere('a.id IN (:auteur)')
                    ->setParameter('auteur', $search->auteur);
            }

            if(!empty($search->nom)){
                $query = $query
                    ->andWhere('l.livre IN (:nom)')
                    ->setParameter('nom', $search->nom);
                    // 
            }
            
            if(!empty($search->string)){
                $query = $query
                    ->andWhere('l.livre LIKE :string')
                    ->setParameter('string', "%{$search->string}%");
            }

            return $query->getQuery()->getResult();
    }
    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
