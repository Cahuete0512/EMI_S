<?php

namespace App\Entity;

use App\Repository\SoireeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=SoireeRepository::class)
 */
class Soiree
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    private $nom;

    /**
     * @ORM\Column (type="integer")
     */
    private $montantTotal;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lieu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity="Participant", inversedBy="soirees")
     * @ORM\JoinTable(name="participants_soirees")
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity="Reglement", mappedBy="soiree")
     */
    private $reglements;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->reglements = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function setParticipants($participants): void
    {
        $this->participants = $participants;
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
    public function getMontantTotal()
    {
        return $this->montantTotal;
    }

    /**
     * @param mixed $montantTotal
     */
    public function setMontantTotal($montantTotal): void
    {
        $this->montantTotal = $montantTotal;
    }

    /**
     * @return mixed
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @param mixed $lieu
     */
    public function setLieu($lieu): void
    {
        $this->lieu = $lieu;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
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
}
