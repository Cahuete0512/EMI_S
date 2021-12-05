<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\ManyToMany(targetEntity="Soiree", mappedBy="participants")
     */
    private $soirees;

    /**
     * @ORM\OneToMany(targetEntity="Reglement", mappedBy="participant")
     */
    private $reglements;

    /**
     * @ORM\OneToMany(targetEntity="Remboursement", mappedBy="crediteur")
     */
    private $remboursementsEffectues;

    /**
     * @ORM\OneToMany(targetEntity="Remboursement", mappedBy="debiteur")
     */
    private $remboursementsRecus;

    public function __construct()
    {
        $this->soirees = new ArrayCollection();
        $this->reglements = new ArrayCollection();
        $this->remboursementsEffectues = new ArrayCollection();
        $this->remboursementsRecus = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getReglements(): ArrayCollection
    {
        return $this->reglements;
    }

    /**
     * @param ArrayCollection $reglements
     */
    public function setReglements(ArrayCollection $reglements): void
    {
        $this->reglements = $reglements;
    }

    /**
     * @return mixed
     */
    public function getSoirees()
    {
        return $this->soirees;
    }

    /**
     * @param mixed $soirees
     */
    public function setSoirees($soirees): void
    {
        $this->soirees = $soirees;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }
}
