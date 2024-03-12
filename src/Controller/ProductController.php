<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Psr\Log\LoggerInterface;

use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	private LoggerInterface $logger;
	
	public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)  {
		$this->entityManager = $entityManager;
		$this->logger = $logger;
	}

    #[Route('/showProduct', name: 'showProduct')]
    public function ajouterLigneAction(Request $request): Response
    {
		$session = $request->getSession() ;
		if (!$session->isStarted())
			$session->start() ;
		$article = $this->entityManager->getReference("App\Entity\Catalogue\Article", $request->query->get("id"));
		return $this->render('product.html.twig', [
            'product' => $this->product,
        ]);
    }
}
