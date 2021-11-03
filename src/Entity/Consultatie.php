<?php

namespace App\Entity;

use App\Repository\ConsultatieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsultatieRepository::class)
 */
class Consultatie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $data;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $diagnostic;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dozaMedicament;

    /**
     * @ORM\ManyToOne(targetEntity=Medic::class, inversedBy="consultatii")
     */
    private $medic;

    /**
     * @ORM\ManyToOne(targetEntity=Pacient::class, inversedBy="consultatii")
     */
    private $pacient;

    /**
     * @ORM\ManyToOne(targetEntity=Medicament::class, inversedBy="consultatii")
     */
    private $medicament;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getDiagnostic(): ?string
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(string $diagnostic): self
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getDozaMedicament(): ?float
    {
        return $this->dozaMedicament;
    }

    public function setDozaMedicament(?float $dozaMedicament): self
    {
        $this->dozaMedicament = $dozaMedicament;

        return $this;
    }

    public function getMedic(): ?Medic
    {
        return $this->medic;
    }

    public function setMedic(?Medic $medic): self
    {
        $this->medic = $medic;

        return $this;
    }

    public function getPacient(): ?Pacient
    {
        return $this->pacient;
    }

    public function setPacient(?Pacient $pacient): self
    {
        $this->pacient = $pacient;

        return $this;
    }

    public function getMedicament(): ?Medicament
    {
        return $this->medicament;
    }

    public function setMedicament(?Medicament $medicament): self
    {
        $this->medicament = $medicament;

        return $this;
    }
}
