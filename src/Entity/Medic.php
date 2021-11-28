<?php

namespace App\Entity;

use App\Repository\MedicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=MedicRepository::class)
 */
class Medic implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\Column(type="string", length=255)
     */
    private $numeMedic;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenumeMedic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $specializare;

    /**
     * @ORM\ManyToMany(targetEntity=Pacient::class, inversedBy="medici")
     * @JoinColumn(onDelete="CASCADE")
     */
    private $pacient;

    /**
     * @ORM\OneToMany(targetEntity=Consultatie::class, mappedBy="medic")
     * @JoinColumn(onDelete="CASCADE")
     */
    private $consultatii;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified;

    public function __construct()
    {
        $this->pacient = new ArrayCollection();
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
        // guarantee every user at least has ROLE_MEDIC
        $roles[] = 'ROLE_MEDIC';

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

    public function getNumeMedic(): ?string
    {
        return $this->numeMedic;
    }

    public function setNumeMedic(string $numeMedic): self
    {
        $this->numeMedic = $numeMedic;

        return $this;
    }

    public function getPrenumeMedic(): ?string
    {
        return $this->prenumeMedic;
    }

    public function setPrenumeMedic(string $prenumeMedic): self
    {
        $this->prenumeMedic = $prenumeMedic;

        return $this;
    }

    public function getSpecializare(): ?string
    {
        return $this->specializare;
    }

    public function setSpecializare(?string $specializare): self
    {
        $this->specializare = $specializare;

        return $this;
    }

    /**
     * @return Collection|Pacient[]
     */
    public function getPacient(): Collection
    {
        return $this->pacient;
    }

    public function addPacient(Pacient $pacient): self
    {
        if (!$this->pacient->contains($pacient)) {
            $this->pacient[] = $pacient;
        }

        return $this;
    }

    public function removePacient(Pacient $pacient): self
    {
        $this->pacient->removeElement($pacient);

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
            $consultatii->setMedic($this);
        }

        return $this;
    }

    public function removeConsultatii(Consultatie $consultatii): self
    {
        if ($this->consultatii->removeElement($consultatii)) {
            // set the owning side to null (unless already changed)
            if ($consultatii->getMedic() === $this) {
                $consultatii->setMedic(null);
            }
        }

        return $this;
    }

    public function isVerified(): ?bool
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
        return $this->prenumeMedic . ' ' . $this->numeMedic;
    }
}
