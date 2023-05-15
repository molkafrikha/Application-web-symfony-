<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function save(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getSponsorByEvenementId(EntityManagerInterface $entityManager, int $evenementId): array
    {
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('s')
            ->from('App\Entity\Sponsoring', 's')
            ->join('App\Entity\Relation1', 'r', 'WITH', 'r.id_sponsor = s.id')
            ->join('App\Entity\Evenement', 'e', 'WITH', 'r.id_evenement = e.id')
            ->where('e.id = :evenementId')
            ->setParameter('evenementId', $evenementId);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findByParticipants():array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.nbparticipants', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findByParticipantsA():array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.nbparticipants', 'ASC')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Evenement[] Returns an array of Evenement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
