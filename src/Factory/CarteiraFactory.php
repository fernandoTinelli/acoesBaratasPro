<?php

namespace App\Factory;

use App\Entity\Carteira;
use App\Entity\Transacao;

class CarteiraFactory
{
    public static function create(Transacao $transacao): Carteira
    {
        return (new Carteira())
            ->setAcao($transacao->getAcao())
            ->setQuantidade($transacao->getQuantidade())
            ->setUser($transacao->getUsuario())
            ->setPrecoMedio($transacao->getValor());
    }
}