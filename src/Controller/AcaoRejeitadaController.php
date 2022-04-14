<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcaoRejeitadaController extends AbstractController
{
    #[Route('/acao/rejeitada', name: 'app_acao_rejeitada')]
    public function index(): Response
    {
        return $this->render('acao_rejeitada/index.html.twig', [
            'controller_name' => 'AcaoRejeitadaController',
        ]);
    }
}
