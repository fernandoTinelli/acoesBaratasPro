<?php

namespace App\Entity;

use App\Repository\TransacaoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransacaoRepository::class)]
class Transacao
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $data = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $valor = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipoTransacao $tipo = null;

    #[ORM\ManyToOne(inversedBy: 'transacaos')]
    private ?User $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'transacaos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Acao $acao = null;

    #[ORM\Column]
    private ?int $quantidade = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getTipo(): ?TipoTransacao
    {
        return $this->tipo;
    }

    public function setTipo(?TipoTransacao $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getAcao(): ?Acao
    {
        return $this->acao;
    }

    public function setAcao(?Acao $acao): self
    {
        $this->acao = $acao;

        return $this;
    }

    public function getQuantidade(): ?int
    {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): self
    {
        $this->quantidade = $quantidade;

        return $this;
    }
}
