<?php

namespace App\Controller\Main;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarteiraController extends BaseController
{
    #[Route('/carteira', name: 'app_carteira')]
    public function index(): Response
    {
        return $this->render('carteira/index.html.twig', [
            'controller_name' => 'CarteiraController',
        ]);
    }
}
