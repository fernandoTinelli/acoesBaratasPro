<?php

namespace App\EventListener;

use App\Controller\EventListener\ExceptionController;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(
        ExceptionEvent $event,
        ExceptionController $exceptionController
    ): void {
        $exception = $event->getThrowable();

        $response = $exceptionController->index($exception);

        $event->setResponse($response);
    }
}