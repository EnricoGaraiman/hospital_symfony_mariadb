<?php

namespace App\Entity;

use App\Repository\ConsultatieRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

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
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"}))
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
     * @JoinColumn(onDelete="CASCADE")
     */
    private $medic;

    /**
     * @ORM\ManyToOne(targetEntity=Pacient::class, inversedBy="consultatii")
     * @JoinColumn(onDelete="CASCADE")
     */
    private $pacient;

    /**
     * @ORM\ManyToOne(targetEntity=Medicament::class, inversedBy="consultatii")
     * @JoinColumn(onDelete="CASCADE")
     */
    private $medicament;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?\DateTime
    {
        return $this->data;
    }

    public function setData(\DateTime $data): self
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
