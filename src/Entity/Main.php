<?php

namespace App\Entity;

use App\Repository\MainRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MainRepository::class)
 */
class Main
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idUser;

    /**
     * @ORM\Column (type="integer", length=11)
     */
    private $montantDoitUser;

    /**
     * @ORM\Column (type="integer", length=11)
     */
    private $montantDonneUser;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nomUser;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $prenomUser;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getMontantDoitUser(): ?int
    {
        return $this->montantDoitUser;
    }

    public function setMontantDoitUser(string $montantDoitUser): self
    {
        $this->montantDoitUser = $montantDoitUser;

        return $this;
    }

    public function getMontantDonneUser(): ?int
    {
        return $this->montantDonneUser;
    }

    public function setMontantDonneUser(string $montantDonneUser): self
    {
        $this->montantDonneUser = $montantDonneUser;

        return $this;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNom(string $nomUser): self
    {
        $this->nomUser = $nomUser;

        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(?string $prenomUser): self
    {
        $this->prenomUser = $prenomUser;

        return $this;
    }
}