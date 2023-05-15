<?php

namespace App\Repository;

use App\Entity\Demandecovoiturage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demandecovoiturage>
 *
 * @method Demandecovoiturage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demandecovoiturage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demandecovoiturage[]    findAll()
 * @method Demandecovoiturage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandecovoiturageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demandecovoiturage::class);
    }
    
    
    /*public function search($lieua, $lieud, $nbplace)
    {
        $qb = $this->createQueryBuilder('r')
            ->join('r.offre', 'o');

        if ($lieua) {
            $qb->andWhere('o.lieua = :lieua')
               ->setParameter('lieua', $lieua);
        }

        if ($lieud) {
            $qb->andWhere('o.lieud = :lieud')
               ->setParameter('lieud', $lieud);
        }

        if ($nbplace) {
            $qb->andWhere('o.nbplace = :nbplace')
               ->setParameter('nbplace', $nbplace);
        }

        return $qb->getQuery()->getResult();
    }
*/


    public function save(Demandecovoiturage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Demandecovoiturage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Demandecovoiturage[] Returns an array of Demandecovoiturage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Demandecovoiturage
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
