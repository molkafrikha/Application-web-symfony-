<?php

namespace App\Repository;
use App\Entity\Sondage;
use App\Entity\Questions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\Bundle\DoctrineBundle\Registry;






/**
 * @extends ServiceEntityRepository<Questions>
 *
 * @method Questions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questions[]    findAll()
 * @method Questions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questions::class);
    }

    /*public function findById($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        $query = $entityManager->createQueryBuilder()
            ->select('a', 'c')
            ->from('App\Entity\Questions', 'a')
            ->join('a.sondage', 'c')
            ->andWhere('c.sondage = :val')
            ->setParameter('val', $id)
            ->getQuery();
    
        return $query->getArrayResult();
    }
    public function findById($sondageId)
    {
        return $this->createQueryBuilder('q')
            ->join('q.sondage', 's')
            ->andWhere('s.sondageId = :sondageId')
            ->setParameter('sondageId', $sondageId)
            ->getQuery()
            ->getResult();
    }*/
   
     

    public function findById($id)
    {
        return $this->createQueryBuilder('a')
            -> join ('a.sondage','c')
            ->addSelect ('c')
            ->andWhere('c.sondageId = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getArrayResult()
        ;
    }
    /*public function findById($sondageId)
    {
    $entityManager=$this->getEntityManager();
    $query=$entityManager
   ->createQuery("SELECT q FROM APP\Entity\Questions q JOIN  q.Sondage s where s.sondageId =:id")
    ->SetParameter('id', $sondage_id);
   
    ->createQuery('SELECT q, s FROM App\Entity\Questions q JOIN q.sondage s WHERE s.sondage_id=:sondageId');

$query->setParameter('sondageId', $sondageId);
return $query->getResult(); 
    
    
    
    } /*




   */ public function save(Questions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function remove(Questions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
   

//    /**
//     * @return Questions[] Returns an array of Questions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Questions
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
