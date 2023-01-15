<?php

namespace App\Model;

use App\Repository\TransacaoRepository;

class EstatisticasModel
{
    private TransacaoRepository $transacaoRepository;

    public function __construct(TransacaoRepository $transacaoRepository)
    {
        $this->transacaoRepository = $transacaoRepository;
    }

    public function getTop5AcoesCompradasMes(): array
    {
        return $this->transacaoRepository->fetchTop5AcoesCompradasMes();
    }

    public function getTop5AcoesVendidasMes(): array
    {
        return $this->transacaoRepository->fetchTop5AcoesVendidasMes();
    }
}