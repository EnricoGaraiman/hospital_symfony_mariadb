<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MedicamentRepository::class)
 */
class Medicament
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
    private $denumire;

    /**
     * @ORM\OneToMany(targetEntity=Consultatie::class, mappedBy="medicament")
     */
    private $consultatie;

    public function __construct()
    {
        $this->consultatie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDenumire(): ?string
    {
        return $this->denumire;
    }

    public function setDenumire(string $denumire): self
    {
        $this->denumire = $denumire;

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
            $consultatie->setMedicament($this);
        }

        return $this;
    }

    public function removeConsultatie(Consultatie $consultatie): self
    {
        if ($this->consultatie->removeElement($consultatie)) {
            // set the owning side to null (unless already changed)
            if ($consultatie->getMedicament() === $this) {
                $consultatie->setMedicament(null);
            }
        }

        return $this;
    }
}
