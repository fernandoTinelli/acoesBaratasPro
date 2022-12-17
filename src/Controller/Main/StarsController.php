<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use App\Factory\StarFactory;
use App\Repository\AcaoRepository;
use App\Repository\StarRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StarsController extends BaseController
{
    #[Route('/stars', name: 'app_stars_index')]
    public function index(UserRepository $userRepository, StarRepository $starRepository): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $acoes = [];
        foreach ($user->getStars() as $star) {
            $acoes[] = $star->getAcao();
        }
        
        $this->setVariable('acoes', $acoes);

        return $this->render('app/stars/index.html.twig', $this->getVariables());
    }

    #[Route('/stars/create/{id}', name: 'app_starts_create', methods: ['GET'])]
    public function create(int $id, UserRepository $userRepository, AcaoRepository $acaoRepository, StarRepository $starRepository): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $acao = $acaoRepository->find($id);

        $star = StarFactory::create($user, $acao);

        if (is_null($starRepository->findOneBy([
            'user' => $user,
            'acao' => $acao
        ]))) {
            $starRepository->save($star, true);
        }

        return $this->redirectToRoute('app_stars_index');
    }

    #[Route('/stars/remove/{id}', name: 'app_starts_remove', methods: ['GET'])]
    public function remove(int $id, UserRepository $userRepository, AcaoRepository $acaoRepository, StarRepository $starRepository): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $acao = $acaoRepository->find($id);

        $star = $starRepository->findOneBy([
            'user' => $user,
            'acao' => $acao
        ]);

        if (!is_null($star)) {
            $starRepository->remove($star, true);
        }

        return $this->redirectToRoute('app_stars_index');
    }
}