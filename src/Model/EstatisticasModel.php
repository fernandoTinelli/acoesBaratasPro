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

        $criteria = (new Criteria())
            ->where(
                Criteria::expr()
                    ->andX(
                        Criteria::expr()->gte('data', new DateTime($firstDayOfMonth)),
                        Criteria::expr()->lte('data', new DateTime($lastDayOfMonth))
                    )
            )
            ->orderBy([
                'data' => 'DESC'
            ])
            ->setMaxResults(5);

        return $this->transacaoRepository
            ->matching($criteria)->toArray();
    }
}