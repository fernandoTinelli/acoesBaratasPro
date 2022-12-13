<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use App\Repository\AcaoRepository;
use App\Repository\CarteiraRepository;
use App\Repository\TipoTransacaoRepository;
use App\Repository\TransacaoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransacoesController extends BaseController
{
    #[Route('/transacoes/{id}', name: 'app_transacoes_index')]
    public function index(
        int $id,
        CarteiraRepository $carteiraRepository,
        TransacaoRepository $transacaoRepository,
        TipoTransacaoRepository $tipoTransacaoRepository,
        AcaoRepository $acaoRepository
    ): Response {
        $carteira = $carteiraRepository->find($id);

        $transcacoes = $transacaoRepository->findBy([
            "acao" => $carteira->getAcao(),
            "usuario" => $carteira->getUser()
        ], ["data" => "DESC"]);

        $tipos = $tipoTransacaoRepository->findAll();

        $this->setVariables([
            'controller_name' => 'TransacoesController',
            'acao' => $carteira->getAcao()->toArray(),
            'transacoes' => $transcacoes,
            'tipos' => $tipos
        ]);

        return $this->render('/app/transacoes/index.html.twig', $this->getVariables());
    }
}
