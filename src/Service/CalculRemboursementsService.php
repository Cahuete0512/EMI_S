<?php

namespace App\Service;

use App\Entity\Participant;
use App\Entity\Remboursement;
use App\Entity\Soiree;
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
     */
    public function calcul(Soiree $soiree){
        $this->logger->info("lancement du calcul");

        // mettre le modulo de côté (divisé par cent pour avoir un "modulo" en centimes)
        $modulo = ($soiree->getMontantTotal() * 100 % $soiree->getParticipants()->count()) / 100;
        $this->logger->info("modulo : " . $modulo);

        // on récupère le montant qui sera divisble par le nom de participants
        $montantSansModulo = $soiree->getMontantTotal() - $modulo;

        // calculer montant à payer pour chacun (sans modulo)
        $montantDivise = $montantSansModulo / $soiree->getParticipants()->count();
        $this->logger->info("montant à payer pour chacun : " . $montantDivise);

        $participants = $soiree->getParticipants()->getValues();
        // calculer les remboursements
        $this->rembourser($participants, $montantDivise);

        // trier la liste par montant recalculé
        $participants = $this->trierParticipantParMontantRecalcule($participants);


        $this->logger->info("################### APRES CALCUL ###################");
        $this->afficher($participants);

        // pour chaque centime en plus du 1er sur le modulo, faire un remboursement de 1 centime aux suivants dans la liste
        for($i = 1; $i<=$modulo*100-1; $i++){
            $this->creerRemboursement(0.01, $participants[$i], $participants[0]);
        }


        $this->logger->info("################### FIN ###################");
        $this->afficher($participants);

    }

    /**
     * @param $participants "l'ensemble des participants"
     * @param $montantDivise
     */
    public function rembourser($participants, $montantDivise){
        // retirer de la liste les participants ayant payé le montant exacte
        foreach ($participants as $key => $participant) {
            if ($participant->getMontantRecalcule() == $montantDivise) {

                $this->logger->info("Supression participant " . $participant->getId());
                unset($participants[$key]);
            }
        }
        $this->logger->info("nombre de participants " . count($participants));

        // si la liste est vide, on sort de la méthode
        if(count($participants) == 1){
            $this->logger->info("Sortie du calcul");
            return;
        }

        // trier la liste par montant payé
        $this->logger->info("Avant");
        $this->afficher($participants);
        $participants = $this->trierParticipantParMontantRecalcule($participants);
        $this->logger->info("Apres");
        $this->afficher($participants);

        // celui qui a payé le moins rembourse à celui qui a payé le plus (dans les limites)
        // calcul du montant
        $debiteur = end($participants);
        $crediteur = $participants[0];
        $montantARembourser = $this->getMontantMaxARembourser($montantDivise, $crediteur, $debiteur);
        $this->logger->info("montant à rembourser : " . $montantARembourser);

        // création du remboursement
        $this->creerRemboursement($montantARembourser, $debiteur, $crediteur);

        $this->logger->info("nb remboursement effectues debiteur : " . count($debiteur->getRemboursementsEffectues()));

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
        $this->logger->info("créditeur : " .$crediteur->getId());
        $this->logger->info("débiteur : " . $debiteur->getId());

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
     * @param Participant $debiteur "la personne qui rembourse le crediteur"
     * @param Participant $crediteur "la personne qui rembourse le crediteur"
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

            $this->logger->info("id " . $debiteur->getId() . " rembourse " . $montantARembourser . " à id " . $crediteur->getId());
        }else{
            $remboursementExistant->setMontant($remboursementExistant->getMontant() + $montantARembourser);
        }
    }
}