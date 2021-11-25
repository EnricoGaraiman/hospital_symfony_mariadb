<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

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
     * @JoinColumn(onDelete="CASCADE")
     */
    private $consultatii;

    public function __construct()
    {
        $this->consultatii = new ArrayCollection();
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
    public function getConsultatii(): Collection
    {
        return $this->consultatii;
    }

    public function addConsultatii(Consultatie $consultatii): self
    {
        if (!$this->consultatii->contains($consultatii)) {
            $this->consultatii[] = $consultatii;
            $consultatii->setMedicament($this);
        }

        return $this;
    }

    public function removeConsultatii(Consultatie $consultatii): self
    {
        if ($this->consultatii->removeElement($consultatii)) {
            // set the owning side to null (unless already changed)
            if ($consultatii->getMedicament() === $this) {
                $consultatii->setMedicament(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->denumire;
    }

}
