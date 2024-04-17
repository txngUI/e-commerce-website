<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Psr\Log\LoggerInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Catalogue\Livre;
use App\Entity\Catalogue\Musique;
use App\Entity\Catalogue\Piste;
use App\Entity\Catalogue\Film;

class RechercheController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	private LoggerInterface $logger;
	
	public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)  {
		$this->entityManager = $entityManager;
		$this->logger = $logger;
	}
	
    #[Route('/afficheRecherche', name: 'afficheRecherche')]
    public function afficheRechercheAction(Request $request): Response
    {
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Article a");
		$articles = $query->getResult();
		return $this->render('recherche.html.twig', [
            'articles' => $articles,
        ]);
    }
	
    #[Route('/afficheRechercheParMotCle', name: 'afficheRechercheParMotCle')]
    public function afficheRechercheParMotCleAction(Request $request): Response
    {
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Article a "
												  ." where a.titre like :motCle");
												  
		$query->setParameter("motCle", "%".$request->query->get("motCle")."%");
		
		$articles = $query->getResult();
		return $this->render('recherche.html.twig', [
            'articles' => $articles,
        ]);
    }

	#[Route('/afficherLivres', name: 'afficherLivres')]
    public function afficherLivresAction(Request $request): Response
    {
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Livre a "
												  ." where a.titre like :motCle");
												  
		$query->setParameter("motCle", "%".$request->query->get("motCle")."%");
		
		$articles = $query->getResult();
		return $this->render('recherche.html.twig', [
            'articles' => $articles,
        ]);
    }

	#[Route('/afficherFilms', name: 'afficherFilms')]
    public function afficherFilmsAction(Request $request): Response
    {
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Film a "
												  ." where a.titre like :motCle");
												  
		$query->setParameter("motCle", "%".$request->query->get("motCle")."%");
		
		$articles = $query->getResult();
		return $this->render('recherche.html.twig', [
            'articles' => $articles,
        ]);
    }

	#[Route('/afficherMusiques', name: 'afficherMusiques')]
    public function afficherMusiquesAction(Request $request): Response
    {
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Musique a "
												  ." where a.titre like :motCle");
												  
		$query->setParameter("motCle", "%".$request->query->get("motCle")."%");
		
		$articles = $query->getResult();
		return $this->render('recherche.html.twig', [
            'articles' => $articles,
        ]);
    }
}
