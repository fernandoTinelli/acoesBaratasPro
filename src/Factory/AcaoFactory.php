<?php

namespace App\Factory;

use App\Entity\Acao;

class AcaoFactory
{
    public function create(array $dataRequest): Acao
    {
        return (new Acao())
            ->setCodigo($dataRequest['_codigo'])
            ->setNome($dataRequest['_nome'])
            ->setPreco($dataRequest['_preco'])
            ->setLiquidez($dataRequest['_liquidez'])
            ->setMargemEbit($dataRequest['_margem_ebit'])
            ->setEvEbit($dataRequest['_ev_ebit']);
    }

    public function createMany(array $dataRequest): array
    {
        $arrayAcoes = [];

        foreach ($dataRequest as $data) {
            $arrayAcoes[] = (new Acao())
                ->setCodigo($data['_codigo'])
                ->setNome($data['_nome'])
                ->setPreco($data['_preco'])
                ->setLiquidez($data['_liquidez'])
                ->setMargemEbit($data['_margem_ebit'])
                ->setEvEbit($data['_ev_ebit']);
        }

        return $arrayAcoes;
    }

    public function update(Acao $acao, array $dataRequest): Acao
    {
        return $acao
            ->setCodigo($dataRequest['_codigo'])
            ->setNome($dataRequest['_nome'])
            ->setPreco($dataRequest['_preco'])
            ->setLiquidez($dataRequest['_liquidez'])
            ->setMargemEbit($dataRequest['_margem_ebit'])
            ->setEvEbit($dataRequest['_ev_ebit']);
    }
}