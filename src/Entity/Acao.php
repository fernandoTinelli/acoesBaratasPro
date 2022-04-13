<?php

namespace App\Entity;

use App\Repository\AcaoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcaoRepository::class)]
class Acao
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $codigo;

    #[ORM\Column(type: 'string', length: 255)]
    private $nome;

    #[ORM\Column(type: 'float')]
    private $preco;

    #[ORM\Column(type: 'float')]
    private $liquidez;

    #[ORM\Column(type: 'float')]
    private $margem_ebit;

    #[ORM\Column(type: 'float')]
    private $ev_ebit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getPreco(): ?float
    {
        return $this->preco;
    }

    public function setPreco(float $preco): self
    {
        $this->preco = $preco;

        return $this;
    }

    public function getLiquidez(): ?float
    {
        return $this->liquidez;
    }

    public function setLiquidez(float $liquidez): self
    {
        $this->liquidez = $liquidez;

        return $this;
    }

    public function getMargemEbit(): ?float
    {
        return $this->margem_ebit;
    }

    public function setMargemEbit(float $margem_ebit): self
    {
        $this->margem_ebit = $margem_ebit;

        return $this;
    }

    public function getEvEbit(): ?float
    {
        return $this->ev_ebit;
    }

    public function setEvEbit(float $ev_ebit): self
    {
        $this->ev_ebit = $ev_ebit;

        return $this;
    }
}
