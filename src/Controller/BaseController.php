<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController
{
    private ?array $variables;

    public function __construct()
    {
        $this->variables = null;
    }

    final protected function setVariableTitle(string $title): BaseController
    {
        $this->variables ??= $this->defaultVariables();
        $this->variables['title'] = $title;
        
        return $this;
    }

    final protected function setVariable(string $key, $value): BaseController
    {
        $this->variables ??= $this->defaultVariables();
        $this->variables[$key] = $value;
        
        return $this;
    }

    final protected function setVariables(array $variables): BaseController
	{
		$this->variables ??= $this->defaultVariables();
		$this->variables = [...$this->variables, ...$variables];
		
		return $this;
	}

    final protected function getVariables(Request $request = null): array
    {
        $this->variables ??= $this->defaultVariables();
        $this->variables = [...$this->variables, 'path' => $request?->server->get('REQUEST_URI')];

        return $this->variables;
    }

    private function defaultVariables(): array
    {
        $variables['user'] = $this->getUser();

        return $variables;
    }
}