<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AcaoRejeitada::class, orphanRemoval: true)]
    private $acaoRejeitadas;

    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Transacao::class)]
    private Collection $transacaos;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Carteira $carteira = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Carteira::class, orphanRemoval: true)]
    private Collection $carteiras;

    public function __construct()
    {
        $this->acaoRejeitadas = new ArrayCollection();
        $this->transacaos = new ArrayCollection();
        $this->carteiras = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if (count($roles) === 0) {
            // guarantee every user that are not ADMIN, at least ROLE_USER
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $acaoRejeitada->setUser($this);
        }

        return $this;
    }

    public function removeAcaoRejeitada(AcaoRejeitada $acaoRejeitada): self
    {
        if ($this->acaoRejeitadas->removeElement($acaoRejeitada)) {
            // set the owning side to null (unless already changed)
            if ($acaoRejeitada->getUser() === $this) {
                $acaoRejeitada->setUser(null);
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
            $transacao->setUsuario($this);
        }

        return $this;
    }

    public function removeTransacao(Transacao $transacao): self
    {
        if ($this->transacaos->removeElement($transacao)) {
            // set the owning side to null (unless already changed)
            if ($transacao->getUsuario() === $this) {
                $transacao->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Carteira>
     */
    public function getCarteiras(): Collection
    {
        return $this->carteiras;
    }

    public function addCarteira(Carteira $carteira): self
    {
        if (!$this->carteiras->contains($carteira)) {
            $this->carteiras->add($carteira);
            $carteira->setUser($this);
        }

        return $this;
    }

    public function removeCarteira(Carteira $carteira): self
    {
        if ($this->carteiras->removeElement($carteira)) {
            // set the owning side to null (unless already changed)
            if ($carteira->getUser() === $this) {
                $carteira->setUser(null);
            }
        }

        return $this;
    }
}
