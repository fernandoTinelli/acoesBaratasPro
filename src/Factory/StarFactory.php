<?php

namespace App\Factory;

use App\Entity\Acao;
use App\Entity\Star;
use App\Entity\User;

class StarFactory
{
    public static function create(User $user, Acao $acao): Star
    {
        return (new Star())
            ->setUser($user)
            ->setAcao($acao);
    }
}