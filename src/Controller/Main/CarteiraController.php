<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use App\Entity\Carteira;
use App\Entity\TipoTransacao;
use App\Entity\Transacao;
use App\Entity\User;
use App\Factory\CarteiraFactory;
use App\Factory\TransacaoFactory;
use App\Helper\ArrayUtils;
use App\Repository\AcaoRepository;
use App\Repository\CarteiraRepository;
use App\Repository\TipoTransacaoRepository;
use App\Repository\TransacaoRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarteiraController extends BaseController
{
    private CarteiraRepository $carteiraRepository;

    public function __construct(CarteiraRepository $carteiraRepository)
    {
        $this->carteiraRepository = $carteiraRepository;
    }

    #[Route('/carteira', name: 'app_carteira', methods: ['GET'])]
    public function index(UserRepository $userRepository, AcaoRepository $acaoRepository, TipoTransacaoRepository $tipoTransacaoRepository): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $carteiras = $user->getCarteiras();

        $acoesCadastradas = $acaoRepository->findAll();
        $acoesCadastradasIndexada = ArrayUtils::indexArray($acoesCadastradas, 'id');

        $tipos = $tipoTransacaoRepository->findAll();

        $this->setVariables([
            'acoes' => $carteiras,
            'acoesCadastradas' => $acoesCadastradas,
            'acoesCadastradasIndexada' => $acoesCadastradasIndexada,
            'tipos' => $tipos
        ]);

        return $this->render('/app/carteira/index.html.twig', $this->getVariables());
    }

    #[Route('/carteira', name: 'app_cateira_transacao_create', methods: ['POST'])]
    public function create(Request $request, TransacaoFactory $transacaoFactory, TransacaoRepository $transacaoRepository): Response
    {
        $user = $this->getUser(); /** @var User $user */

        if (!$this->validarTransacao($request)) {
            return $this->redirectToRoute('app_carteira');
        }

        $transacao = $transacaoFactory->create($request->request->all(), $user);

        $acaoCarteira = $this->carteiraRepository->findOneBy([
            'acao' => $transacao->getAcao(), 
            'user' => $transacao->getUsuario()
        ]); 

        // Validar Regra de Negócios Transação
        if (!$this->validarRegrasNegocioTransacao($acaoCarteira, $transacao)) {
            return $this->redirectToRoute('app_carteira');
        }

        $transacaoRepository->add($transacao, true);

        $this->addFlash('success', 'Transação adicionada com sucesso');

        // Sumarizar Transações na Tabela Carteira
        /** @var Carteira $acaoCarteira */
        if (!is_null($acaoCarteira)) {
            $valorTotalOld = $acaoCarteira->getQuantidade() * $acaoCarteira->getPrecoMedio();
            $valorTotalNew = $transacao->getValor() * $transacao->getQuantidade();
            $novaQuantidade = $acaoCarteira->getQuantidade();

            if ($transacao->getTipo()->getId() == TipoTransacao::$COMPRA) {
                $novaQuantidade += $transacao->getQuantidade();
                $novoPrecoMedio = ($valorTotalOld + $valorTotalNew) / $novaQuantidade;
            } else {
                $novaQuantidade -= $transacao->getQuantidade();
                $novoPrecoMedio = ($valorTotalOld - $valorTotalNew) / $novaQuantidade;
            }

            $acaoCarteira->setQuantidade($novaQuantidade);
            $acaoCarteira->setPrecoMedio($novoPrecoMedio);

            if ($novaQuantidade == 0) {
                // remover acao da carteira
                $this->carteiraRepository->delete($acaoCarteira, true);
            } else {
                $this->carteiraRepository->add($acaoCarteira, true);
            }

            return $this->redirectToRoute('app_carteira');
        }

        // Não é necessário nenhum cálculo adicional, pois é a primeira transação
        $acaoCarteira = CarteiraFactory::create($transacao);

        $this->carteiraRepository->add($acaoCarteira, true);

        return $this->redirectToRoute('app_carteira');
    }

    #[Route('/carteira/delete/{id}', name: 'app_cateira_transacao_delete', methods: ['GET'])]
    public function delete(int $id, TransacaoRepository $transacaoRepository, CarteiraRepository $carteiraRepository): Response
    {
        $transacoes = $transacaoRepository->findBy([
            'acao' => $id
        ]);
        foreach ($transacoes as $transacao) {
            $transacaoRepository->remove($transacao);
        }
        $transacaoRepository->flush();

        $carteira = $carteiraRepository->findOneBy([
            'acao' => $id
        ]);
        if (!is_null($carteira)) {
            $carteiraRepository->remove($carteira, true);
        }

        $this->addFlash('success' ,'Ação excluída com sucesso');

        return $this->redirectToRoute('app_carteira');
    }

    private function validarTransacao(Request $request): bool
    {
        $isValid = true;

        if (empty($request->request->get('_acao'))) {
            $this->addFlash(
                'danger',
                'Deve-se selecionar uma ação para a Transação.'
            );
            $isValid = false;
        }

        if (empty($request->request->get('_data'))) {
            $this->addFlash(
                'danger',
                'Deve-se selecionar uma data para a Transação.'
            );
            $isValid = false;
        }

        if (empty($request->request->get('_tipo'))) {
            $this->addFlash(
                'danger',
                'Deve-se informar o tipo da Transação.'
            );
            $isValid = false;
        }

        if (empty($request->request->get('_quantidade'))) {
            $this->addFlash(
                'danger',
                'Deve-se informar a quantidade para a Transação.'
            );
            $isValid = false;
        }

        if (empty($request->request->get('_valor'))) {
            $this->addFlash(
                'danger',
                'Deve-se informar o valor para a Transação.'
            );
            $isValid = false;
        }

        return $isValid;
    }

    private function validarRegrasNegocioTransacao(?Carteira $acaoCarteira, Transacao $transacao): bool
    {
        $isVenda = $transacao->getTipo()->getId() == TipoTransacao::$VENDA;

        if (is_null($acaoCarteira) && $isVenda) {
            $this->addFlash('danger', 'Você não tem ativos suficientes para realizar tal transação.');
            return false;
        }

        if ($isVenda && $acaoCarteira->getQuantidade() < $transacao->getQuantidade()) {
            $this->addFlash('danger', 'Você não tem ativos suficientes para realizar tal transação.');
            return false;
        }

        return true;
    }
}
