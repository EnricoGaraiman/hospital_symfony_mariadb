<?php

namespace App\Entity;

use App\Repository\PacientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PacientRepository::class)
 */
class Pacient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $asigurare;

    /**
     * @ORM\ManyToMany(targetEntity=Medic::class, mappedBy="pacient")
     */
    private $medici;

    /**
     * @ORM\OneToMany(targetEntity=Consultatie::class, mappedBy="pacient")
     */
    private $consultatie;

    public function __construct()
    {
        $this->medici = new ArrayCollection();
        $this->consultatie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAsigurare(): ?string
    {
        return $this->asigurare;
    }

    public function setAsigurare(?string $asigurare): self
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
    public function getConsultatie(): Collection
    {
        return $this->consultatie;
    }

    public function addConsultatie(Consultatie $consultatie): self
    {
        if (!$this->consultatie->contains($consultatie)) {
            $this->consultatie[] = $consultatie;
            $consultatie->setPacient($this);
        }

        return $this;
    }

    public function removeConsultatie(Consultatie $consultatie): self
    {
        if ($this->consultatie->removeElement($consultatie)) {
            // set the owning side to null (unless already changed)
            if ($consultatie->getPacient() === $this) {
                $consultatie->setPacient(null);
            }
        }

        return $this;
    }
}
