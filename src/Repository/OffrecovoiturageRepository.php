<?php

namespace App\Repository;

use App\Entity\Offrecovoiturage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offrecovoiturage>
 *
 * @method Offrecovoiturage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offrecovoiturage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offrecovoiturage[]    findAll()
 * @method Offrecovoiturage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffrecovoiturageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offrecovoiturage::class);
    }

    public function save(Offrecovoiturage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offrecovoiturage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Offrecovoiturage[] Returns an array of Offrecovoiturage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offrecovoiturage
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
