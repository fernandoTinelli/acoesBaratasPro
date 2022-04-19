<?php

namespace App\Repository;

use App\Entity\Acao;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Acao|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acao|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acao[]    findAll()
 * @method Acao[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcaoRepository extends ServiceEntityRepository
{
    public static $PAGINATOR_PER_PAGE = 10;

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

    public function getAcaoPaginator(int $offset, string $order = 'ASC'): Paginator
    {
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.codigo', $order)
            ->setMaxResults(AcaoRepository::$PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;

        return new Paginator($query);
    }

    public function findAllSimplified(array $columns = null)
    {
        if (!is_null($columns)) {
            $columns = array_map(fn($value) => 'a.' . $value, $columns);
        }

        return $this->createQueryBuilder('a')
                ->select($columns ?? [])
                ->getQuery()
                ->execute();
    }

    public function findAllWithJoin()
    {
        return $this->createQueryBuilder('a')
                ->innerJoin('a.acaoRejeitada', 'ar')
                ->getQuery()
                ->getResult();
    }

    public function findAllWithLeftJoin()
    {
        return $this->createQueryBuilder('a')
                ->leftJoin('a.acaoRejeitada', 'ar')
                ->orderBy('a.codigo', 'ASC')
                ->getQuery()
                ->getResult();
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
