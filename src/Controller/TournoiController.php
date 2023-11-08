<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Importez la classe EntityManagerInterface
use Doctrine\ORM\EntityManagerInterface;

// Importez la classe ManagerRegistry
use Doctrine\Persistence\ManagerRegistry;

// Importez les classes Evenement et Tournoi
use App\Entity\Evenement;
use App\Entity\Tournoi;

//Importez 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Form\TournoiType;

class TournoiController extends AbstractController
{
                    /*PREMIERE METHODE EN UTILISANT "EntityManagerInterface"*/

    /*
    //propriétéqui stockera une instance de l'EntityManager
    private $em;

    //Utilisation de l'EntityManager injecté dans le contrôleur pour récupérer tous
    //les enregistrements des entités Evenement et Tournoi

    //constructeur de la classe TournoiController
    public function __construct(EntityManagerInterface $em) 
    {
        $this->entityManager = $em;
    }

    #[Route('/tournoi', name: 'app_tournoi')]
    public function index1(): Response
    {
        //Récupération de tous les enregistrements des entités Evenement et Tournoi depuis la base de données
        $listEv = $this->entityManager->getRepository(Evenement::class)->findAll();
        $listTr = $this->entityManager->getRepository(Tournoi::class)->findAll();

        //passer les données à la vue Twig en tant que variable
        return $this->render('tournoi/listeTournois.html.twig', [
            'evt' => $listEv,
            'tr' => $listTr
        ]);
    }
    */


                    /*DEUXIEME METHODE EN UTILISANT "ManagerRegistry"*/

    #[Route('/tournoi')]
    public function index2(ManagerRegistry $doctrine): Response
    {
        $listEv = $doctrine->getRepository(Evenement::class)->findAll();
        $listTr = $doctrine->getRepository(Tournoi::class)->findAll();
        
        //passer les données à la vue Twig en tant que variable
        return $this->render('tournoi/listeTournois.html.twig', [
            'evt' => $listEv,
            'tr' => $listTr
        ]);
    }


                    /*AJOUTER UN EVENEMENT AYANT UN NOM ET PAS DE DATES*/

    // route n'autorisant pas d'espaces dans le nom de l'événement
    //#[Route("/tournoi/newEvt/{nom<[0-9a-zA-Z]+>}", name:"newEvt")]

    // route autorisant des espaces dans le nom de l'événement
    #[Route("/tournoi/newEvt/{nom<[0-9a-zA-Z\s]+>}", name: "newEvt")]
    public function indexNewEvent($nom, ManagerRegistry $doctrine): Response {
        //On crée un nouveau Evenement
        $ev = new Evenement();

        //On lui passe le nom de l'événement
        $ev->setNom($nom);

        //On recupère l'entity manager
        $em = $doctrine->getManager();

        //On met $ev dans le tampon des objets persistants
        $em->persist($ev);

        //On lance la transaction dans la BD (on met l'élément dans la BD)
        $em->flush();

        return new Response("Evénement " . $nom . " créé avec succès avec l'identifiant: " . $ev->getId());
    }


                    /*AJOUTER UN TOURNOI A UN EVENEMENT EXISTANT*/
    
    // id de l’événement et les attributs de tournois fournis comme paramètres
    // la route autorise des espaces dans le nom du tournoi
    // ici 'desc' est optionnel
    #[Route("/tournoi/newTnoi/{evtid<[0-9]+>}/{nom<[0-9a-zA-Z ]+>}/{desc?}", name:"newTnoi")]
    public function indexNewTournoi($id, $nom, $desc, ManagerRegistry $doctrine): Response {
        
        //On crée un nouveau Tournoi
        $tnoi = new Tournoi();

        //On lui passe le nom et la description du tournoi
        $tnoi->setNom($nom);
        $tnoi->setDescription($desc);

        //On recupère l'entity manager
        $em = $doctrine->getManager();

        $idEv = (int)$id;

        //On cherche l'événement associé à l'id renseigné
        $ev = $em->find(Evenement::class, $idEv);

        if ($ev === null){
            return new Response("Aucun événement n'est associé à l'id: ". $idEv . ". Le tournoi n'a pas été crée.");
        } else{

            //On lui passe l'id de l'événement
            $tnoi->setEv($ev);

            //On met $tnoi dans le tampon des objets persistants
            $em->persist($tnoi);

            //On lance la transaction dans la BD (on met l'élément dans la BD)
            $em->flush();

            return new Response("Tournoi " . $tnoi . " créé avec succès avec l'identifiant: " . $tnoi->getId());
        }

    }


                    /*METHODE POUR AJOUTER UN TOURNOI EN UTILISANT UN FORMULAIRE*/
    
    /*METHODE 1: UTILISATION D'AbstractController*/               
    //AbstractController possède une méthode createFormBuilder()
    //permettant de créer le formulaire à partir d’une entité                

    #[Route("/tournoi/saisieTnoi/{evtid<[0-9]+>}",name:"saisieTnoi")]
    public function saisieTnoi($evtid): Response {

        //On crée un nouveau Tournoi
        $tnoi=new Tournoi();

        //On initialise le nom et la description avec une chaine vide
        $tnoi->setNom("");
        $tnoi->setDescription("");

        //On crée le formulaire en utilisant méthode createFormBuilder() de AbstractController
        $form = $this->createFormBuilder($tnoi)
        ->add('nom', TextType::class)
        ->add('description', TextType::class)
        ->add('sauver', SubmitType::class, ['label' => 'Créer Tournoi !'])
        ->getForm(); // le formulaire est créé

        return $this->render('tournoi/saisieTnoi.html.twig', [
            'formulaire' => $form->createView()]
        );
    }


    /*METHODE 2: UTILISATION DU FORMULAIRE SYMFONY*/

    #[Route("/tournoi/saisirTnoi", name:"saisirTnoi")]
    public function saisirTnoi(): Response {
        
        //On crée un nouveau tournoi
        $tnoi=new Tournoi();

        //On crée le formulaire en utilisant la méthode createForm()
        $form = $this->createForm(TournoiType::class, $tnoi);
        
        return $this->render('tournoi/saisieTnoi.html.twig', [
            'formulaire' => $form->createView()]
        );
    }

}
