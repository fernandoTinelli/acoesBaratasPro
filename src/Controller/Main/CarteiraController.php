<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use App\Repository\AcaoRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarteiraController extends BaseController
{
    #[Route('/carteira', name: 'app_carteira')]
    public function index(UserRepository $userRepository, AcaoRepository $acaoRepository): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $carteiras = $user->getCarteiras();

        $acoesCadastradas = $acaoRepository->findAll();

        $this->setVariables([
            'acoes' => $carteiras,
            'acoesCadastradas' => $acoesCadastradas
        ]);

        return $this->render('/app/carteira/index.html.twig', $this->getVariables());
    }

    #[Route('/cateira/{id<\d+>?', name: 'app_cateira_transacao_create', methods: ['POST'])]
    public function create(Request $request)
    {
        dd($request);
    }
}
