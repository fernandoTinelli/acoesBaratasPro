<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    private ?array $variables;

    public function __construct()
    {
        $this->variables = null;
    }

    final protected function setVariable(string $key, $value): BaseController
    {
        $this->variables ??= $this->defaultVariables();
        $this->variables[$key] = $value;
        
        return $this;
    }

    final protected function getVariables(): array
    {
        $this->variables ??= $this->defaultVariables();

        return $this->variables;
    }

    private function defaultVariables(): array
    {
        $variables['user'] = $this->getUser();

        return $variables;
    }
}