<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Soiree;
use App\Form\ParticipantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{

    #[Route('participant/ajouter/{id}', name:'ajouter_participant')]
    public function ajouter($id, Request $request){
        //Créer un participant
        $participant = new Participant();

        //Créer le formulaire pour ce participant
        $form = $this->createForm(ParticipantType::class, $participant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $repo = $this->getDoctrine()->getRepository(Soiree::class);
            $soiree=$repo->find($id);

            $participant->setSoiree($soiree);

            //On récupère l'entity manager
            $em = $this->getDoctrine()->getManager();

            //Je dis à l'entity manager que je veux enregistrer mon participant
            $em->persist($participant);

            //je déclenche la requête
            $em->flush();

            //je retourne à l'accueil
            return $this->redirectToRoute("acceder_soiree", ["id" => $id]);
        }

        return $this->render("participant/ajouter.html.twig", [
            "formulaire"=> $form->createView()
        ]);
    }

    #[Route('participant/modifier/{idSoiree}/{idParticipant}', name:'modifier_participant')]
    public function modifier($idSoiree, $idParticipant,Request $request){

        //Aller chercher la soirée
        $repo = $this->getDoctrine()->getRepository(Participant::class);
        $participant=$repo->find($idParticipant);

        //Créer le formulaire pour cette soirée
        $form = $this->createForm(ParticipantType::class, $participant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //On récupère l'entity manager
            $em = $this->getDoctrine()->getManager();

            //Je dis à l'entity manager que je veux enregistrer ma soirée modifiée
            $em->persist($participant);

            //je déclenche la requête
            $em->flush();

            //je retourne à l'accueil
            return $this->redirectToRoute("acceder_soiree", ["id" => $idSoiree]);
        }

        return $this->render("participant/modifier.html.twig", [
            "formulaire"=> $form->createView()
        ]);
    }
  }


