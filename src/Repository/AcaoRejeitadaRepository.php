<?php

namespace App\Repository;

use App\Entity\AcaoRejeitada;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AcaoRejeitada|null find($id, $lockMode = null, $lockVersion = null)
 * @method AcaoRejeitada|null findOneBy(array $criteria, array $orderBy = null)
 * @method AcaoRejeitada[]    findAll()
 * @method AcaoRejeitada[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcaoRejeitadaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AcaoRejeitada::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AcaoRejeitada $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(AcaoRejeitada $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function removeAll()
    {
        $query = $this->_em->getConnection()->prepare('delete from acao_rejeitada');
        $query->executeQuery();
    }

    public function flush()
    {
        $this->_em->flush();
    }

    // /**
    //  * @return AcaoRejeitada[] Returns an array of AcaoRejeitada objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AcaoRejeitada
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
