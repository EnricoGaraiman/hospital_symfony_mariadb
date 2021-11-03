<?php

namespace App\Entity;

use App\Repository\MedicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MedicRepository::class)
 */
class Medic
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
     */
    private $pacient;

    public function __construct()
    {
        $this->pacient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
