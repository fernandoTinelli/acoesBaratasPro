<?php

namespace App\Entity;

use App\Repository\AcaoRejeitadaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcaoRejeitadaRepository::class)]
class AcaoRejeitada
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $motivo;

    #[ORM\OneToOne(inversedBy: 'acaoRejeitada', targetEntity: Acao::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $acao;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotivo(): ?string
    {
        return $this->motivo;
    }

    public function setMotivo(?string $motivo): self
    {
        $this->motivo = $motivo;

        return $this;
    }

    public function getAcao(): ?Acao
    {
        return $this->acao;
    }

    public function setAcao(Acao $acao): self
    {
        $this->acao = $acao;

        return $this;
    }
}
