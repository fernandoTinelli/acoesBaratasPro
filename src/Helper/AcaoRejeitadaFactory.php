<?php

namespace App\Helper;

use App\Entity\AcaoRejeitada;

class AcaoRejeitadaFactory
{
    public function create(string $motivo = '')
    {
        return (new AcaoRejeitada())
            ->setMotivo($motivo);
    }
}