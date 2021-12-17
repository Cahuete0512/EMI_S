<?php

namespace App\Service;

use App\Entity\Participant;
use App\Entity\Remboursement;
use App\Entity\Soiree;
use Exception;
use Psr\Log\LoggerInterface;

class CalculRemboursementsService {

    protected $logger;

    /**
     * @param LoggerInterface $logger "pour afficher les logs"
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Soiree $soiree
     * @throws Exception si le nb de participants est inférieur à 2
     */
    public function calcul(Soiree $soiree){
        $participants = $soiree->getParticipants()->getValues();

        if (count($participants)<2){
            $this->logger->warning("Participants inférieurs à deux. Le calcul n'est pas possible !");
            throw new Exception("Participants inférieurs à deux. Le calcul n'est pas possible !");
        }

        // mettre le modulo de côté (divisé par cent pour avoir un "modulo" en centimes)
        $modulo = ($soiree->getMontantTotal() * 100 % $soiree->getParticipants()->count()) / 100;

        // on récupère le montant qui sera divisble par le nom de participants
        $montantSansModulo = $soiree->getMontantTotal() - $modulo;

        // calculer montant à payer pour chacun (sans modulo)
        $montantDivise = $montantSansModulo / $soiree->getParticipants()->count();

        // calculer les remboursements
        $this->rembourser($participants, $montantDivise);

        // trier la liste par montant recalculé
        $participants = $this->trierParticipantParMontantRecalcule($participants);

        // pour chaque centime en plus du 1er sur le modulo, faire un remboursement de 1 centime aux suivants dans la liste
        for($i = 1; $i<=$modulo*100-1; $i++){
            $this->creerRemboursement(0.01, $participants[$i], $participants[0]);
        }
    }

    /**
     * @param $participants "l'ensemble des participants"
     * @param $montantDivise
     */
    public function rembourser($participants, $montantDivise){
        // retirer de la liste les participants ayant payé le montant exacte
        foreach ($participants as $key => $participant) {
            if ($participant->getMontantRecalcule() == $montantDivise) {
                unset($participants[$key]);
            }
        }

        // si la liste est vide, on sort de la méthode
        if(count($participants) <= 1){
            return;
        }

        // trier la liste par montant payé
        $participants = $this->trierParticipantParMontantRecalcule($participants);

        // celui qui a payé le moins rembourse à celui qui a payé le plus (dans les limites)
        // calcul du montant
        $debiteur = end($participants);
        $crediteur = $participants[0];
        $montantARembourser = $this->getMontantMaxARembourser($montantDivise, $crediteur, $debiteur);

        // création du remboursement
        $this->creerRemboursement($montantARembourser, $debiteur, $crediteur);

        // on rappelle la méthode
        $this->rembourser($participants, $montantDivise);
    }

    /**
     * @param $montantDivise
     * @param $crediteur "la personne qui avance les frais"
     * @param $debiteur "la personne qui rembourse le crediteur"
     * @return mixed
     */
    public function getMontantMaxARembourser($montantDivise, $crediteur, $debiteur){
        $montantMaxACrediter = $crediteur->getMontantRecalcule() - $montantDivise;
        $montantMaxADebiter = $montantDivise - $debiteur->getMontantRecalcule();

        if($montantMaxACrediter > $montantMaxADebiter){
            return $montantMaxADebiter;
        }else{
            return $montantMaxACrediter;
        }
    }

    /**
     * @param $participants
     * @return mixed
     */
    public function trierParticipantParMontantRecalcule($participants): mixed
    {
        usort($participants,
            function ($a, $b) {
                if ($a->getMontantRecalcule() == $b->getMontantRecalcule()) {
                    return 0;
                }
                return $a->getMontantRecalcule() < $b->getMontantRecalcule() ? 1 : -1;
            });
        return $participants;
    }

    /**
     * @param float $montantARembourser
     * @param Participant $debiteur la personne qui rembourse le crediteur
     * @param Participant $crediteur la personne qui rembourse le crediteur
     */
    public function creerRemboursement(float $montantARembourser, Participant $debiteur, Participant $crediteur): void
    {
        $remboursementExistant = null;
        if(!empty($debiteur->getRemboursementsEffectues())) {
            foreach ($debiteur->getRemboursementsEffectues() as $remboursement) {
                if ($remboursement->getCrediteur()->getId() == $crediteur->getId()) {
                    $remboursementExistant = $remboursement;
                }
            }
        }

        if($remboursementExistant == null) {
            $remboursement = new Remboursement();
            $remboursement->setMontant($montantARembourser);
            $remboursement->setCrediteur($crediteur);
            $remboursement->setDebiteur($debiteur);

            $debiteur->addRemboursementEffectue($remboursement);
            $crediteur->addRemboursementRecu($remboursement);
        }else{
            $remboursementExistant->setMontant($remboursementExistant->getMontant() + $montantARembourser);
        }
    }
}