<?php

namespace App\Controller;

use App\Entity\Soiree;
use App\Form\SoireeType;
use App\Service\CalculRemboursementsService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Exception;

class SoireeController extends AbstractController
{

    protected $logger;

    /**
     * @param LoggerInterface $logger "pour afficher les logs"
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

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
    public function ajouter(Request $request){
        //créer une soirée vide
        $soiree = new Soiree();

        //Crééer le formulaire pour cette soirée
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

    #[Route('soiree/modifier/{id}', name:'modifier')]
    public function modifier($id,Request $request){

        //Aller chercher la soirée
        $repo = $this->getDoctrine()->getRepository(Soiree::class);
        $soiree=$repo->find($id);

        //Créer le formulaire pour cette soirée
        $form = $this->createForm(SoireeType::class, $soiree);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //On récupère l'entity manager
            $em = $this->getDoctrine()->getManager();

            //Je dis à l'entity manager que je veux enregistrer ma soirée modifiée
            $em->persist($soiree);

            //je déclenche la requête
            $em->flush();

            //je retourne à l'accueil
            return $this->redirectToRoute("home");
        }

        return $this->render("soiree/modifier.html.twig", [
            "formulaire"=> $form->createView()
        ]);
    }

    #[Route('soiree/supprimer/{id}', name:'supprimer')]
    public function supprimer($id){

        //Aller chercher la soirée
        $repo = $this->getDoctrine()->getRepository(Soiree::class);
        $soiree=$repo->find($id);

        //On récupère l'entity manager
        $em = $this->getDoctrine()->getManager();

        //Je dis à l'entity manager que je veux supprimer ma soirée
        $em->remove($soiree);

        //je déclenche la requête
        $em->flush();

        //je retourne à l'accueil
        return $this->redirectToRoute("home");
    }

    #[Route('soiree/acceder/{id}', name:'acceder_soiree')]
    public function acceder($id){

        //Aller chercher la soirée
        $repo = $this->getDoctrine()->getRepository(Soiree::class);
        $soiree=$repo->find($id);

        //On récupère l'entity manager
        $em = $this->getDoctrine()->getManager();

        return $this->render('soiree/acceder.html.twig', [
            'soiree'=>$soiree
        ]);
    }


    #[Route('soiree/calculer/{id}', name:'calculer_soiree')]
    public function calculer($id, CalculRemboursementsService $calculRemboursementsService){
        //Aller chercher la soirée
        $repo = $this->getDoctrine()->getRepository(Soiree::class);
        $soiree=$repo->find($id);

        try{
            $calculRemboursementsService->calcul($soiree);

        }catch(Exception $e){
            $this->logger->info($e);
            $this->addFlash("warning", '$e->getMessage()');
        }

        return $this->render('soiree/remboursement.html.twig', [
            'soiree'=>$soiree
        ]);
    }
}


