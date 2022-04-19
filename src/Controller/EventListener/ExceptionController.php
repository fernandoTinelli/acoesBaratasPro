<?php

namespace App\Controller\EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExceptionController extends AbstractController
{
    public function index(Throwable $exception): Response
    {
        return new Response();
    }
}