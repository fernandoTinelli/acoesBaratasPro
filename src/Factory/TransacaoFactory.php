<?php

namespace App\Factory;

use App\Entity\Acao;
use App\Entity\Transacao;
use App\Entity\User;
use App\Repository\AcaoRepository;
use App\Repository\TipoTransacaoRepository;
use DateTime;

class TransacaoFactory
{
    private AcaoRepository $acaoRepository;

    private TipoTransacaoRepository $tipoTransacaoRepository;

    public function __construct(AcaoRepository $acaoRepository, TipoTransacaoRepository $tipoTransacaoRepository)
    {
        $this->acaoRepository = $acaoRepository;
        $this->tipoTransacaoRepository = $tipoTransacaoRepository;
    }

    public function create(array $dataRequest, User $user): Transacao
    {
        return (new Transacao())
            ->setAcao($this->acaoRepository->findOneBy(['id' => $dataRequest['_acao']]))
            ->setData(new DateTime($dataRequest['_data']))
            ->setTipo($this->tipoTransacaoRepository->findOneBy(['id' => $dataRequest['_tipo']]))
            ->setQuantidade($dataRequest['_quantidade'])
            ->setValor($dataRequest['_valor'])
            ->setUsuario($user);
    }

    // public function createMany(array $dataRequest): array
    // {
    //     $arrayAcoes = [];

    //     foreach ($dataRequest as $data) {
    //         $arrayAcoes[] = (new Acao())
    //             ->setCodigo($data['_codigo'])
    //             ->setNome($data['_nome'])
    //             ->setPreco($data['_preco'])
    //             ->setLiquidez($data['_liquidez'])
    //             ->setMargemEbit($data['_margem_ebit'])
    //             ->setEvEbit($data['_ev_ebit']);
    //     }

    //     return $arrayAcoes;
    // }

    // public function update(Acao $acao, array $dataRequest): Acao
    // {
    //     return $acao
    //         ->setCodigo($dataRequest['_codigo'])
    //         ->setNome($dataRequest['_nome'])
    //         ->setPreco($dataRequest['_preco'])
    //         ->setLiquidez($dataRequest['_liquidez'])
    //         ->setMargemEbit($dataRequest['_margem_ebit'])
    //         ->setEvEbit($dataRequest['_ev_ebit']);
    // }
}