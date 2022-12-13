<?php

namespace App\Entity;

use App\Repository\AcaoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'acao', targetEntity: AcaoRejeitada::class, orphanRemoval: true)]
    private $acaoRejeitadas;

    #[ORM\OneToMany(mappedBy: 'acao', targetEntity: Transacao::class, orphanRemoval: true)]
    private Collection $transacaos;

    public function __construct()
    {
        $this->acaoRejeitadas = new ArrayCollection();
        $this->transacaos = new ArrayCollection();
    }

    private function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

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

    /**
     * @return Collection<int, AcaoRejeitada>
     */
    public function getAcaoRejeitadas(): Collection
    {
        return $this->acaoRejeitadas;
    }

    public function addAcaoRejeitada(AcaoRejeitada $acaoRejeitada): self
    {
        if (!$this->acaoRejeitadas->contains($acaoRejeitada)) {
            $this->acaoRejeitadas[] = $acaoRejeitada;
            $acaoRejeitada->setAcao($this);
        }

        return $this;
    }

    public function removeAcaoRejeitada(AcaoRejeitada $acaoRejeitada): self
    {
        if ($this->acaoRejeitadas->removeElement($acaoRejeitada)) {
            // set the owning side to null (unless already changed)
            if ($acaoRejeitada->getAcao() === $this) {
                $acaoRejeitada->setAcao(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transacao>
     */
    public function getTransacaos(): Collection
    {
        return $this->transacaos;
    }

    public function addTransacao(Transacao $transacao): self
    {
        if (!$this->transacaos->contains($transacao)) {
            $this->transacaos->add($transacao);
            $transacao->setAcao($this);
        }

        return $this;
    }

    public function removeTransacao(Transacao $transacao): self
    {
        if ($this->transacaos->removeElement($transacao)) {
            // set the owning side to null (unless already changed)
            if ($transacao->getAcao() === $this) {
                $transacao->setAcao(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "nome" => $this->nome,
            "codigo" => $this->codigo
        ];
    }
}
