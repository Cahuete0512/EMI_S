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
     * @ORM\ManyToOne(targetEntity="Soiree", inversedBy="participants")
     */
    private $soiree;

    /**
     * @ORM\Column(type="float")
     */
    private $montantPaye;

    private $remboursementsEffectues;

    private $remboursementsRecus;

    public function __construct()
    {
        $this->soiree = new ArrayCollection();
        $this->remboursementsEffectues = [];
        $this->remboursementsRecus = array();
    }

    /**
     * @return float
     */
    public function getMontantPaye()
    {
        return $this->montantPaye;
    }

    /**
     * @param float $montantPaye
     */
    public function setMontantPaye($montantPaye): void
    {
        $this->montantPaye = $montantPaye;
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

    public function getRemboursementsEffectues()
    {
        return $this->remboursementsEffectues;
    }

    public function addRemboursementEffectue($remboursement){
        if(empty($this->remboursementsEffectues)){
            $this->remboursementsEffectues = [$remboursement];
        }else {
            array_push($this->remboursementsEffectues, $remboursement);
        }
    }

    public function addRemboursementRecu($remboursement){
        if(empty($this->remboursementsRecus)){
            $this->remboursementsRecus = [$remboursement];
        }else {
            array_push($this->remboursementsRecus, $remboursement);
        }
    }

    public function getRemboursementsRecus()
    {
        return $this->remboursementsRecus;
    }

    public function getMontantRecalcule()
    {
        $sommeRemboursementsEffectues = 0;
        if(!empty($this->remboursementsEffectues)) {
            foreach ($this->remboursementsEffectues as $re) {
                $sommeRemboursementsEffectues += $re->getMontant();
            }
        }

        $sommeRemboursementsRecus = 0;
        if(!empty($this->remboursementsRecus)) {
            foreach ($this->remboursementsRecus as $rr) {
                $sommeRemboursementsRecus += $rr->getMontant();
            }
        }

        return $this->montantPaye + $sommeRemboursementsEffectues - $sommeRemboursementsRecus;
    }
}
