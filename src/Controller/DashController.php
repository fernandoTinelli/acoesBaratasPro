<?php

namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashController extends BaseController
{
    public function __construct()
    {
        parent::__construct();   
    }

    #[Route('/dash', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', $this->getVariables());
    }
}
