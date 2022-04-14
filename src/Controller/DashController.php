<?php

namespace App\Controller;

use App\Trait\DefaultVariablesControllers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashController extends AbstractController
{
    use DefaultVariablesControllers;

    #[Route('/dash', name: 'app_index')]
    public function index(): Response
    {
        $variables = $this->defaultVariables();

        return $this->render('app/index.html.twig', $variables);
    }
}
