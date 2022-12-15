<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use App\Entity\Transacao;
use App\Repository\CarteiraRepository;
use App\Repository\TipoTransacaoRepository;
use App\Repository\TransacaoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransacoesController extends BaseController
{
    #[Route('/transacoes/{id}', name: 'app_transacoes_index', methods: ['GET'])]
    public function index(
        int $id,
        CarteiraRepository $carteiraRepository,
        TransacaoRepository $transacaoRepository,
        TipoTransacaoRepository $tipoTransacaoRepository
    ): Response {
        $carteira = $carteiraRepository->find($id);

        $transcacoes = $transacaoRepository->findBy([
            "acao" => $carteira->getAcao(),
            "usuario" => $carteira->getUser()
        ], ["data" => "DESC"]);

        $tipos = $tipoTransacaoRepository->findAll();

        $this->setVariables([
            'controller_name' => 'TransacoesController',
            'carteira' => $carteira,
            'acao' => $carteira->getAcao()->toArray(),
            'transacoes' => $transcacoes,
            'tipos' => $tipos
        ]);

        return $this->render('/app/transacoes/index.html.twig', $this->getVariables());
    }

    #[Route('/transacao/{id}/delete/{transacao}', name: 'app_transacoes_delete', methods: ['GET'])]
    public function delete(
        int $id,
        int $transacao,
        CarteiraRepository $carteiraRepository,
        TransacaoRepository $transacaoRepository
    ) {
        $transacaoObj = $transacaoRepository->find($transacao);

        $carteira = $carteiraRepository->find($id);

        // Sumarizar Transações na Tabela Carteira
        $transacoes = $transacaoRepository->findBy([
            'usuario' => $carteira->getUser(),
            'acao' => $carteira->getAcao()
        ]);

        $novoPrecoMedio = 0;
        $quantidadeRemovida = 0;
        foreach ($transacoes as $tran) {
            /** @var Transacao $tran */
            if ($transacao == $tran->getId()) {
                $quantidadeRemovida = $tran->getQuantidade();
                continue;
            }

            $novoPrecoMedio += $tran->getValor();
        }

        $novaQuantidade = $carteira->getQuantidade() - $quantidadeRemovida;
        $novoPrecoMedio /= $novaQuantidade;

        $carteira->setQuantidade($carteira->getQuantidade() - $quantidadeRemovida);
        $carteira->setPrecoMedio($novoPrecoMedio);

        $transacaoRepository->remove($transacaoObj, true);
        $carteiraRepository->add($carteira, true);

        return $this->redirectToRoute('app_transacoes_index', ['id' => $id]);
    }
}
