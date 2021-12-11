<?php

namespace App\Entity;

class Remboursement
{

    private $id;

    private $montant;


    private $crediteur;


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
