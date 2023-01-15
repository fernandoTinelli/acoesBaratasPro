<?php

namespace App\Repository;

use App\Entity\Transacao;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transacao>
 *
 * @method Transacao|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transacao|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transacao[]    findAll()
 * @method Transacao[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransacaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transacao::class);
    }

    public function add(Transacao $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Transacao $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function fetchTop5AcoesCompradasMes(): array
    {
        $firstDayOfMonth = date("Y-m-01");
        $lastDayOfMonth = (new DateTime())
            ->modify("last day of this month")
            ->format('Y-m-d');

        $stmt = $this->getEntityManager()->getConnection()->prepare('
            SELECT t.acao_id, SUM(t.quantidade) AS total, a.nome
            FROM acoesbaratas.transacao t JOIN acoesbaratas.acao a ON t.acao_id = a.id
            WHERE t.data BETWEEN "' . $firstDayOfMonth . '" AND "' . $lastDayOfMonth . '" AND t.tipo_id = 1
            GROUP BY t.acao_id 
            ORDER BY total DESC
            LIMIT 0, 5'
        );

        $result = $stmt->executeQuery();

        if ($result->rowCount() == 0) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    public function fetchTop5AcoesVendidasMes(): array
    {
        $firstDayOfMonth = date("Y-m-01");
        $lastDayOfMonth = (new DateTime())
            ->modify("last day of this month")
            ->format('Y-m-d');

        $stmt = $this->getEntityManager()->getConnection()->prepare('
            SELECT t.acao_id, SUM(t.quantidade) AS total, a.nome
            FROM acoesbaratas.transacao t JOIN acoesbaratas.acao a ON t.acao_id = a.id
            WHERE t.data BETWEEN "' . $firstDayOfMonth . '" AND "' . $lastDayOfMonth . '" AND t.tipo_id = 2
            GROUP BY t.acao_id 
            ORDER BY total DESC
            LIMIT 0, 5'
        );

        $result = $stmt->executeQuery();

        if ($result->rowCount() == 0) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

//    /**
//     * @return Transacao[] Returns an array of Transacao objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Transacao
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
