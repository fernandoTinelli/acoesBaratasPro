<?php

namespace App\Trait;

trait DefaultVariablesControllers
{
    private function defaultVariables(): array
    {
        $variables = array();

        $variables['user'] = $this->getUser();

        return $variables;
    }
}