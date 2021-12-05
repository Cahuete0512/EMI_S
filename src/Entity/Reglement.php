<?php

namespace App\Entity;

use App\Repository\ReglementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReglementRepository::class)
 */
class Reglement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="Participant", inversedBy="reglements")
     */
    private $participant;

    /**
     * @ORM\ManyToOne(targetEntity="Soiree", inversedBy="reglements")
     */
    private $soiree;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getParticipant(): ?string
    {
        return $this->participant;
    }

    public function setParticipant(string $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSoiree()
    {
        return $this->soiree;
    }

    /**
     * @param mixed $soiree
     */
    public function setSoiree($soiree): void
    {
        $this->soiree = $soiree;
    }
}
