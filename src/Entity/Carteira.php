<?php

namespace App\Entity;

use App\Repository\CarteiraRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarteiraRepository::class)]
class Carteira
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Acao $acao = null;

    #[ORM\Column]
    private ?int $quantidade = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $preco_medio = null;

    #[ORM\ManyToOne(inversedBy: 'carteiras')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrecoMedio(): ?string
    {
        return $this->preco_medio;
    }

    public function setPrecoMedio(string $preco_medio): self
    {
        $this->preco_medio = $preco_medio;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
