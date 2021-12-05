<?php

namespace App\Entity;

use App\Repository\RemboursementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RemboursementRepository::class)
 */
class Remboursement
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
     * One Customer has One Cart.
     * @ORM\ManyToOne (targetEntity="Participant", inversedBy="remboursementsEffectues")
     */
    private $crediteur;

    /**
     * One Customer has One Cart.
     * @ORM\ManyToOne (targetEntity="Participant", inversedBy="remboursementsRecus")
     */
    private $debiteur;

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

    /**
     * @return mixed
     */
    public function getCrediteur()
    {
        return $this->crediteur;
    }

    /**
     * @param mixed $crediteur
     */
    public function setCrediteur($crediteur): void
    {
        $this->crediteur = $crediteur;
    }

    /**
     * @return mixed
     */
    public function getDebiteur()
    {
        return $this->debiteur;
    }

    /**
     * @param mixed $debiteur
     */
    public function setDebiteur($debiteur): void
    {
        $this->debiteur = $debiteur;
    }
}
