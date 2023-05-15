<?php

namespace App\Repository;

use App\Entity\Sponsoring;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sponsoring>
 *
 * @method Sponsoring|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sponsoring|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sponsoring[]    findAll()
 * @method Sponsoring[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SponsoringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sponsoring::class);
    }

    public function save(Sponsoring $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sponsoring $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getEvenementBySponsorId(EntityManagerInterface $entityManager, int $sponsorId): array
    {
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('e')
            ->from('App\Entity\Evenement', 'e')
            ->join('App\Entity\Relation1', 'r', 'WITH', 'r.id_evenement = e.id')
            ->join('App\Entity\Sponsoring', 's', 'WITH', 'r.id_sponsor = s.id')
            ->where('s.id = :sponsorId')
            ->setParameter('sponsorId', $sponsorId);

        return $queryBuilder->getQuery()->getResult();
    }
    public function getSponsorbyNom($sponsor){
        return $this->createQueryBuilder('sponsoring')
            ->where('sponsoring.sponsor LIKE :sponsor')
            ->setParameter('sponsor', '%'.$sponsor.'%')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Sponsoring[] Returns an array of Sponsoring objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sponsoring
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
