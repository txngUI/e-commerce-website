<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Psr\Log\LoggerInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Catalogue\Livre;
use App\Entity\Catalogue\Musique;
use App\Entity\Catalogue\Film;

class AdminController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	private LoggerInterface $logger;
	
	public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)  {
		$this->entityManager = $entityManager;
		$this->logger = $logger;
	}
	
    #[Route('/admin/musiques', name: '0')]
    public function adminMusiquesAction(Request $request): Response
    {
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Musique a");
		$articles = $query->getResult();
		return $this->render('admin.musiques.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/admin/livres', name: 'adminLivres')]
    public function adminLivresAction(Request $request): Response
    {
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Livre a");
		$articles = $query->getResult();
		return $this->render('admin.livres.html.twig', [
            'articles' => $articles,
        ]);
    }

	#[Route('/admin/films', name: 'adminFilms')]
    public function adminFilmsAction(Request $request): Response
    {
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Film a");
		$articles = $query->getResult();
		return $this->render('admin.films.html.twig', [
            'articles' => $articles,
        ]);
    }

	#[Route('/admin/films/supprimer', name: 'adminFilmsSupprimer')]
    public function adminFilmsSupprimerAction(Request $request): Response
    {
		$entityArticle = $this->entityManager->getReference("App\Entity\Catalogue\Article", $request->query->get("id"));
		if ($entityArticle !== null) {
			$this->entityManager->remove($entityArticle);
			$this->entityManager->flush();
		}
		return $this->redirectToRoute("adminFilms") ;
    }

	#[Route('/admin/films/ajouter', name: 'adminFilmsAjouter')]
    public function adminFilmsAjouterAction(Request $request): Response
    {
		$entity = new Film() ;
		$formBuilder = $this->createFormBuilder($entity);
		$formBuilder->add("titre", TextType::class) ;
		$formBuilder->add("realisateur", TextType::class) ;
		$formBuilder->add("prix", NumberType::class) ;
		$formBuilder->add("disponibilite", IntegerType::class) ;
		$formBuilder->add("image", TextType::class) ;
		$formBuilder->add("dateDeParution", TextType::class) ;
		$formBuilder->add("valider", SubmitType::class) ;
		// Generate form
		$form = $formBuilder->getForm();
		
		$form->handleRequest($request) ;
		
		if ($form->isSubmitted()) {
			$entity = $form->getData() ;
			$entity->setId(hexdec(uniqid()));
			$this->entityManager->persist($entity);
			$this->entityManager->flush();
			return $this->redirectToRoute("adminFilms") ;
		}
		else {
			return $this->render('admin.form.html.twig', [
				'form' => $form->createView(),
			]);
		}
    }

	#[Route('/admin/commandes', name: 'adminCommandes')]
	public function adminCommandesAction(Request $request): Response
	{
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Commande\Commande a");
		$commandes = $query->getResult();
		return $this->render('admin.commandes.html.twig', [
			'commandes' => $commandes,
		]);
	}

	#[Route('/admin/commandes/modifier', name: 'adminCommandesModifier')]
    public function adminCommandesModifierAction(Request $request): Response
    {
		$entity = $this->entityManager->getReference("App\Entity\Commande\Commande", $request->query->get("id"));
		if ($entity === null) 
			$entity = $this->entityManager->getReference("App\Entity\Commande\Commande", $request->request->get("id"));
		if ($entity !== null) {
			$formBuilder = $this->createFormBuilder($entity);
			$formBuilder->add("id", HiddenType::class) ;
			$formBuilder->add("nom", TextType::class) ;
			$formBuilder->add("prenom", TextType::class) ;
			$formBuilder->add("email", TextType::class) ;
			$formBuilder->add("adress", TextType::class) ;
			$formBuilder->add("telephone", TextType::class) ;
			$formBuilder->add("montant", NumberType::class) ;
			$formBuilder->add("valider", SubmitType::class) ;

			// Generate form
			$form = $formBuilder->getForm();
			
			$form->handleRequest($request) ;
			
			if ($form->isSubmitted()) {
				$entity = $form->getData();
				$this->entityManager->persist($entity);
				$this->entityManager->flush();
				return $this->redirectToRoute("adminCommandes") ;
			}
			else {
				return $this->render('admin.form.html.twig', [
					'form' => $form->createView(),
				]);
			}
		}
		else {
			return $this->redirectToRoute("adminCommandes") ;
		}
    }

	#[Route('/admin/commandes/supprimer', name: 'adminCommandesSupprimer')]
    public function adminCommandesSupprimerAction(Request $request): Response
    {
		$entityArticle = $this->entityManager->getReference("App\Entity\Commande\Commande", $request->query->get("id"));
		if ($entityArticle !== null) {
			$this->entityManager->remove($entityArticle);
			$this->entityManager->flush();
		}
		return $this->redirectToRoute("adminCommandes") ;
    }

	
    #[Route('/admin/musiques/supprimer', name: 'adminMusiquesSupprimer')]
    public function adminMusiquesSupprimerAction(Request $request): Response
    {
		$entityArticle = $this->entityManager->getReference("App\Entity\Catalogue\Article", $request->query->get("id"));
		if ($entityArticle !== null) {
			$this->entityManager->remove($entityArticle);
			$this->entityManager->flush();
		}
		return $this->redirectToRoute("adminMusiques") ;
    }
	
    #[Route('/admin/livres/supprimer', name: 'adminLivresSupprimer')]
    public function adminLivresSupprimerAction(Request $request): Response
    {
		$entityArticle = $this->entityManager->getReference("App\Entity\Catalogue\Article", $request->query->get("id"));
		if ($entityArticle !== null) {
			$this->entityManager->remove($entityArticle);
			$this->entityManager->flush();
		}
		return $this->redirectToRoute("adminLivres") ;
    }

    #[Route('/admin/livres/ajouter', name: 'adminLivresAjouter')]
    public function adminLivresAjouterAction(Request $request): Response
    {
		$entity = new Livre() ;
		$formBuilder = $this->createFormBuilder($entity);
		$formBuilder->add("titre", TextType::class) ;
		$formBuilder->add("auteur", TextType::class) ;
		$formBuilder->add("prix", NumberType::class) ;
		$formBuilder->add("disponibilite", IntegerType::class) ;
		$formBuilder->add("image", TextType::class) ;
		$formBuilder->add("ISBN", TextType::class) ;
		$formBuilder->add("nbPages", IntegerType::class) ;
		$formBuilder->add("dateDeParution", TextType::class) ;
		$formBuilder->add("valider", SubmitType::class) ;
		// Generate form
		$form = $formBuilder->getForm();
		
		$form->handleRequest($request) ;
		
		if ($form->isSubmitted()) {
			$entity = $form->getData() ;
			$entity->setId(hexdec(uniqid()));
			$this->entityManager->persist($entity);
			$this->entityManager->flush();
			return $this->redirectToRoute("adminLivres") ;
		}
		else {
			return $this->render('admin.form.html.twig', [
				'form' => $form->createView(),
			]);
		}
    }
	
    #[Route('/admin/musiques/ajouter', name: 'adminMusiquesAjouter')]
    public function adminMusiquesAjouterAction(Request $request): Response
    {
		$entity = new Musique() ;
		$formBuilder = $this->createFormBuilder($entity);
		$formBuilder->add("titre", TextType::class) ;
		$formBuilder->add("artiste", TextType::class) ;
		$formBuilder->add("prix", NumberType::class) ;
		$formBuilder->add("disponibilite", IntegerType::class) ;
		$formBuilder->add("image", TextType::class) ;
		$formBuilder->add("dateDeParution", TextType::class) ;
		$formBuilder->add("valider", SubmitType::class) ;
		// Generate form
		$form = $formBuilder->getForm();
		
		$form->handleRequest($request) ;
		
		if ($form->isSubmitted()) {
			$entity = $form->getData() ;
			$entity->setId(hexdec(uniqid()));
			$this->entityManager->persist($entity);
			$this->entityManager->flush();
			return $this->redirectToRoute("adminMusiques") ;
		}
		else {
			return $this->render('admin.form.html.twig', [
				'form' => $form->createView(),
			]);
		}
    }

    #[Route('/admin/livres/modifier', name: 'adminLivresModifier')]
    public function adminLivresModifierAction(Request $request): Response
    {
		$entity = $this->entityManager->getReference("App\Entity\Catalogue\Livre", $request->query->get("id"));
		if ($entity === null) 
			$entity = $this->entityManager->getReference("App\Entity\Catalogue\Livre", $request->request->get("id"));
		if ($entity !== null) {
			$formBuilder = $this->createFormBuilder($entity);
			$formBuilder->add("id", HiddenType::class) ;
			$formBuilder->add("titre", TextType::class) ;
			$formBuilder->add("auteur", TextType::class) ;
			$formBuilder->add("prix", NumberType::class) ;
			$formBuilder->add("disponibilite", IntegerType::class) ;
			$formBuilder->add("image", TextType::class) ;
			$formBuilder->add("ISBN", TextType::class) ;
			$formBuilder->add("nbPages", IntegerType::class) ;
			$formBuilder->add("dateDeParution", TextType::class) ;
			$formBuilder->add("valider", SubmitType::class) ;
			// Generate form
			$form = $formBuilder->getForm();
			
			$form->handleRequest($request) ;
			
			if ($form->isSubmitted()) {
				$entity = $form->getData() ;
				$this->entityManager->persist($entity);
				$this->entityManager->flush();
				return $this->redirectToRoute("adminLivres") ;
			}
			else {
				return $this->render('admin.form.html.twig', [
					'form' => $form->createView(),
				]);
			}
		}
		else {
			return $this->redirectToRoute("adminLivres") ;
		}
    }
	
    #[Route('/admin/musiques/modifier', name: 'adminMusiquesModifier')]
    public function adminMusiquesModifierAction(Request $request): Response
    {
		$entity = $this->entityManager->getReference("App\Entity\Catalogue\Musique", $request->query->get("id"));
		if ($entity === null) 
			$entity = $this->entityManager->getReference("App\Entity\Catalogue\Musique", $request->request->get("id"));
		if ($entity !== null) {
			$formBuilder = $this->createFormBuilder($entity);
			$formBuilder->add("id", HiddenType::class) ;
			$formBuilder->add("titre", TextType::class) ;
			$formBuilder->add("artiste", TextType::class) ;
			$formBuilder->add("prix", NumberType::class) ;
			$formBuilder->add("disponibilite", IntegerType::class) ;
			$formBuilder->add("image", TextType::class) ;
			$formBuilder->add("dateDeParution", TextType::class) ;
			$formBuilder->add("valider", SubmitType::class) ;
			// Generate form
			$form = $formBuilder->getForm();
			
			$form->handleRequest($request) ;
			
			if ($form->isSubmitted()) {
				$entity = $form->getData();
				$this->entityManager->persist($entity);
				$this->entityManager->flush();
				return $this->redirectToRoute("adminMusiques") ;
			}
			else {
				return $this->render('admin.form.html.twig', [
					'form' => $form->createView(),
				]);
			}
		}
		else {
			return $this->redirectToRoute("adminMusiques") ;
		}
    }
}
