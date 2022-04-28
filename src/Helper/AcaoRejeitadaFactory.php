<?php

namespace App\Helper;

use App\Entity\Acao;
use App\Entity\AcaoRejeitada;
use App\Entity\User;

class AcaoRejeitadaFactory
{
    public function create(User $user, Acao $acao, string $motivo = '')
    {
        return 
            (new AcaoRejeitada())
                ->setMotivo($motivo)
                ->setUser($user)
                ->setAcao($acao);
    }
}