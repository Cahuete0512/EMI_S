<?php

namespace App\Controller;

use App\Entity\Soiree;
use App\Form\SoireeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SoireeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        //Récupérer le repository de soirée
        $repository=$this->getDoctrine()->getRepository(Soiree::class);

        //Lire la BDD
        $soirees = $repository->findAll(); //Un select *

        return $this->render('soiree/index.html.twig', [
            'soirees'=>$soirees
        ]);
    }

    #[Route('ajouter', name:'ajouter')]
    public function soiree_ajouter(Request $request){
        //crééer une soirée vide
        $soiree = new Soiree();

        //Crééer le formulaire pour cette catégorie
        $form = $this->createForm(SoireeType::class, $soiree);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //On récupère l'entity manager
            $em = $this->getDoctrine()->getManager();

            //Je dis à l'entity manager que je veux enregistrer ma soirée
            $em->persist($soiree);

            //je déclenche la requête
            $em->flush();

            //je retourne à l'accueil
            return $this->redirectToRoute("home");
        }

        return $this->render("soiree/ajouter.html.twig", [
            "formulaire"=> $form->createView()
        ]);
    }

    #[Route('categorie/modifier/{id}', name:'categorie_modifier')]
    public function categorie_modifier($id,Request $request){

        //Aller chercher la catégorie
        $repo = $this->getDoctrine()->getRepository(SoireeType::class);
        $categorie=$repo->find($id);

        //Crééer le formulaire pour cette catégorie
        $form = $this->createForm(SoireeType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //On récupère l'entity manager
            $em = $this->getDoctrine()->getManager();

            //Je dis à l'entity manager que je veux enregistrer ma catégorie
            $em->persist($categorie);

            //je déclenche la requête
            $em->flush();

            //je retourne à l'accueil
            return $this->redirectToRoute("home");
        }

        return $this->render("categorie/modifier.html.twig", [
            "formulaire"=> $form->createView()
        ]);
    }
}


