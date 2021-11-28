<?php

namespace App\Entity;

use App\Repository\PacientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=PacientRepository::class)
 * @UniqueEntity(fields={"email"}, message="Există deja un cont cu acest email")
 */
class Pacient implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=13, unique=true)
     */
    private $cnp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numePacient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenumePacient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresa;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $asigurare;

    /**
     * @ORM\ManyToMany(targetEntity=Medic::class, mappedBy="pacient")
     * @JoinColumn(onDelete="CASCADE")
     */
    private $medici;

    /**
     * @ORM\OneToMany(targetEntity=Consultatie::class, mappedBy="pacient")
     * @JoinColumn(onDelete="CASCADE")
     */
    private $consultatii;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    public function __construct()
    {
        $this->medici = new ArrayCollection();
        $this->consultatii = new ArrayCollection();
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_PACIENT
        $roles[] = 'ROLE_PACIENT';

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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCnp(): ?string
    {
        return $this->cnp;
    }

    public function setCnp(string $cnp): self
    {
        $this->cnp = $cnp;

        return $this;
    }

    public function getNumePacient(): ?string
    {
        return $this->numePacient;
    }

    public function setNumePacient(string $numePacient): self
    {
        $this->numePacient = $numePacient;

        return $this;
    }

    public function getPrenumePacient(): ?string
    {
        return $this->prenumePacient;
    }

    public function setPrenumePacient(string $prenumePacient): self
    {
        $this->prenumePacient = $prenumePacient;

        return $this;
    }

    public function getAdresa(): ?string
    {
        return $this->adresa;
    }

    public function setAdresa(?string $adresa): self
    {
        $this->adresa = $adresa;

        return $this;
    }

    public function getAsigurare(): ?bool
    {
        return $this->asigurare;
    }

    public function setAsigurare(?bool $asigurare): self
    {
        $this->asigurare = $asigurare;

        return $this;
    }

    /**
     * @return Collection|Medic[]
     */
    public function getMedici(): Collection
    {
        return $this->medici;
    }

    public function addMedici(Medic $medici): self
    {
        if (!$this->medici->contains($medici)) {
            $this->medici[] = $medici;
            $medici->addPacient($this);
        }

        return $this;
    }

    public function removeMedici(Medic $medici): self
    {
        if ($this->medici->removeElement($medici)) {
            $medici->removePacient($this);
        }

        return $this;
    }

    /**
     * @return Collection|Consultatie[]
     */
    public function getConsultatii(): Collection
    {
        return $this->consultatii;
    }

    public function addConsultatii(Consultatie $consultatii): self
    {
        if (!$this->consultatii->contains($consultatii)) {
            $this->consultatii[] = $consultatii;
            $consultatii->setPacient($this);
        }

        return $this;
    }

    public function removeConsultatii(Consultatie $consultatii): self
    {
        if ($this->consultatii->removeElement($consultatii)) {
            // set the owning side to null (unless already changed)
            if ($consultatii->getPacient() === $this) {
                $consultatii->setPacient(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function __toString()
    {
        return $this->prenumePacient . ' ' . $this->numePacient;
    }

}
