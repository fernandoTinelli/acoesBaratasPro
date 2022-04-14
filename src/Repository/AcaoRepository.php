<?php

namespace App\Repository;

use App\Entity\Acao;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Acao|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acao|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acao[]    findAll()
 * @method Acao[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acao::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Acao $entity, bool $flush = true): void
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
    public function remove(Acao $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function flush()
    {
        $this->_em->flush();
    }

    public function removeAll()
    {
        $query = $this->_em->getConnection()->prepare('delete from acao');
        $query->executeQuery();
    }

    // /**
    //  * @return Acao[] Returns an array of Acao objects
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
    public function findOneBySomeField($value): ?Acao
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
