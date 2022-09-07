<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use App\Entity\User;
use App\Factory\TransacaoFactory;
use App\Repository\AcaoRepository;
use App\Repository\TipoTransacaoRepository;
use App\Repository\TransacaoRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarteiraController extends BaseController
{
    #[Route('/carteira', name: 'app_carteira', methods: ['GET'])]
    public function index(UserRepository $userRepository, AcaoRepository $acaoRepository, TipoTransacaoRepository $tipoTransacaoRepository): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $carteiras = $user->getCarteiras();

        $acoesCadastradas = $acaoRepository->findAll();

        $tipos = $tipoTransacaoRepository->findAll();

        $this->setVariables([
            'acoes' => $carteiras,
            'acoesCadastradas' => $acoesCadastradas,
            'tipos' => $tipos
        ]);

        return $this->render('/app/carteira/index.html.twig', $this->getVariables());
    }

    #[Route('/carteira', name: 'app_cateira_transacao_create', methods: ['POST'])]
    public function create(Request $request, TransacaoRepository $transacaoRepository, UserRepository $userRepository, TransacaoFactory $transacaoFactory)
    {
        $user = $this->getUser(); /** @var User $user */

        if (!$this->validarTransacao($request)) {
            return $this->redirectToRoute('app_carteira');
        }

        $transacao = $transacaoFactory->create($request->request->all(), $user);

        dd($transacao);

        return $this->json(['teste' => 'teste']);
    }

    public function validarTransacao(Request $request): bool
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
}
