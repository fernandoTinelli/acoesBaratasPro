<?php

namespace App\Model;

use App\Repository\TransacaoRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;

class EstatisticasModel
{
    private TransacaoRepository $transacaoRepository;

    public function __construct(TransacaoRepository $transacaoRepository)
    {
        $this->transacaoRepository = $transacaoRepository;
    }

    public function getTop5AcoesCompradasMes(): array
    {
        $firstDayOfMonth = date("Y-m-01");
        $lastDayOfMonth = (new DateTime())
            ->modify("last day of this month")
            ->format('Y-m-d');

        $qb = $this->transacaoRepository->createQueryBuilder('a');
        $query = $qb
            ->select('a.nome')
            ->where(
                'a.data between (\'' . (new DateTime($firstDayOfMonth))->format('Y/m/d') . '\', \'' . (new DateTime($lastDayOfMonth))->format('Y/m/d') . '\')'
            )
            ->groupBy('a.id')
            ->orderBy('a.data', 'DESC')
            ->setMaxResults(5)
            ->getQuery();

        dd($query);

        // $criteria = (new Criteria())
        //     ->where(
        //         Criteria::expr()
        //             ->andX(
        //                 Criteria::expr()->gte('data', new DateTime($firstDayOfMonth)),
        //                 Criteria::expr()->lte('data', new DateTime($lastDayOfMonth))
        //             )
        //     )
        //     ->orderBy([
        //         'data' => 'DESC'
        //     ])
        //     ->setMaxResults(5);

        dd($query->getResult());

        return [];
    }
}