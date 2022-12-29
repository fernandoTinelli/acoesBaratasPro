<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstatisticasController extends BaseController
{
    #[Route('/estatisticas', name: 'app_estatisticas_index')]
    public function index(): Response
    {
        return $this->render('app/estatisticas/index.html.twig', $this->getVariables());
    }
}
