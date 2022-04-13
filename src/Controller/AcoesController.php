<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcoesController extends AbstractController
{
    #[Route('/acoes', name: 'app_acoes')]
    public function index(): Response
    {
        return $this->render('app/acoes/index.html.twig', [
            'controller_name' => 'AcoesController',
            'user' => $this->getUser()
        ]);
    }

    #[Route('/acoes/novo', name: 'app_novo', methods: ['GET'])]
    public function add()
    {
        return $this->render('app/acoes/new.html.twig', [
            'controller_name' => 'AcoesController',
            'user' => $this->getUser()
        ]);
    }
}
