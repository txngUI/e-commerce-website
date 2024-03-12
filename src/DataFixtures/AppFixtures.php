<?php

namespace App\DataFixtures;

use DateTime;
use DatePeriod;
use DateInterval;

use App\Entity\Catalogue\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use GuzzleHttp\Client;

use DeezerAPI\Search;

use App\Entity\Catalogue\Livre;
use App\Entity\Catalogue\Musique;
use App\Entity\Catalogue\Piste;

use Psr\Log\LoggerInterface;

class AppFixtures extends Fixture
{
	protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
	
    public function load(ObjectManager $manager): void
    {
		if (count($manager->getRepository("App\Entity\Catalogue\Article")->findAll()) == 0) {
			$ebay = new Ebay($this->logger);
			$ebay->setCategory('CDs');
			//$keywords = 'Ibrahim Maalouf' ;
			//$ebay->setCategory('Livres');
			$keywords = 'Lena Raine' ;

			$formattedResponse = $ebay->findItemsAdvanced($keywords, 10);
			
			$xml = simplexml_load_string($formattedResponse);

			if ($xml !== false) {
				foreach ($xml->children() as $child_1) {
					if ($child_1->getName() === "item") {
						if ($ebay->getParentCategoryIdById($child_1->primaryCategory->categoryId) == $ebay->getParentCategoryIdByName("Livres")) {
							$entityLivre = new Livre();
							$entityLivre->setId((int) $child_1->itemId);
							$title = $ebay->getItemSpecific("Book Title", $child_1->itemId) ;
							if ($title == null) $title = $child_1->title ;
							$entityLivre->setTitre($title);
							$author = $ebay->getItemSpecific("Author", $child_1->itemId) ;
							if ($author == null) $author = "" ;
							$entityLivre->setAuteur($author);
							$entityLivre->setISBN("");
							$entityLivre->setPrix((float) $child_1->sellingStatus->currentPrice); 
							$entityLivre->setDisponibilite(1);
							$entityLivre->setImage($child_1->galleryURL);
							$manager->persist($entityLivre);
							$manager->flush();
						}
						if ($ebay->getParentCategoryIdById($child_1->primaryCategory->categoryId) == $ebay->getParentCategoryIdByName("CDs")){
							$entityMusique = new Musique();
							$entityMusique->setId((int) $child_1->itemId);
							$title = $ebay->getItemSpecific("Release Title", $child_1->itemId) ;
							if ($title == null) $title = $child_1->title ;
							$entityMusique->setTitre($title);
							$artist = $ebay->getItemSpecific("Artist", $child_1->itemId) ;
							if ($artist == null) $artist = "" ;
							$entityMusique->setArtiste($artist);
							$entityMusique->setDateDeParution("");
							$entityMusique->setPrix((float) $child_1->sellingStatus->currentPrice); 
							$entityMusique->setDisponibilite(1);
							$entityMusique->setImage($child_1->galleryURL);
							if (!isset($albums)) {
								$deezerSearch = new Search($keywords);
								$artistes = $deezerSearch->searchArtist() ;
								$albums = $deezerSearch->searchAlbumsByArtist($artistes[0]->getId()) ;
							}
							$j = 0 ;
							$sortir = ($j==count($albums)) ;
							$albumTrouve = false ;
							while (!$sortir) {
								$titreDeezer = str_replace(" ","",mb_strtolower($albums[$j]->title)) ;
								$titreEbay = str_replace(" ","",mb_strtolower($entityMusique->getTitre())) ;
								$titreDeezer = str_replace("-","",$titreDeezer) ;
								$titreEbay = str_replace("-","",$titreEbay) ;
								$albumTrouve = ($titreDeezer == $titreEbay) ;
								if (mb_strlen($titreEbay) > mb_strlen($titreDeezer))
									$albumTrouve = $albumTrouve || (mb_strpos($titreEbay, $titreDeezer) !== false) ;
								 if (mb_strlen($titreDeezer) > mb_strlen($titreEbay))
									$albumTrouve = $albumTrouve || (mb_strpos($titreDeezer, $titreEbay) !== false) ;
								$j++ ;
								$sortir = $albumTrouve || ($j==count($albums)) ;
							}
							if ($albumTrouve) {
								$tracks = $deezerSearch->searchTracksByAlbum($albums[$j-1]->getId()) ;
								foreach ($tracks as $track) {
									$entityPiste = new Piste();
									$entityPiste->setTitre($track->title);
									$entityPiste->setMp3($track->preview);
									$manager->persist($entityPiste);
									$manager->flush();
									$entityMusique->addPiste($entityPiste) ;
								}
							}
							$manager->persist($entityMusique);
							$manager->flush();
						}
					}
				}
			}
			$entityLivre = new Livre();
			$entityLivre->setId(55677821);
			$entityLivre->setTitre("Le seigneur des anneaux");
			$entityLivre->setAuteur("J.R.R. TOLKIEN");
			$entityLivre->setISBN("2075134049");
			$entityLivre->setNbPages(736);
			$entityLivre->setDateDeParution("03/10/19");
			$entityLivre->setPrix("8.90");
			$entityLivre->setDisponibilite(1);
			$entityLivre->setImage("/images/51O0yBHs+OL._SL140_.jpg");
			$manager->persist($entityLivre);
			$entityLivre = new Livre();
			$entityLivre->setId(55897821);
			$entityLivre->setTitre("Un paradis trompeur");
			$entityLivre->setAuteur("Henning Mankell");
			$entityLivre->setISBN("275784797X");
			$entityLivre->setNbPages(400);
			$entityLivre->setDateDeParution("09/10/14");
			$entityLivre->setPrix("6.80");
			$entityLivre->setDisponibilite(1);
			$entityLivre->setImage("/images/71uwoF4hncL._SL140_.jpg");
			$manager->persist($entityLivre);
			$entityLivre = new Livre();
			$entityLivre->setId(56299459);
			$entityLivre->setTitre("Dôme tome 1");
			$entityLivre->setAuteur("Stephen King");
			$entityLivre->setISBN("2212110685");
			$entityLivre->setNbPages(840);
			$entityLivre->setDateDeParution("06/03/13");
			$entityLivre->setPrix("8.90");
			$entityLivre->setDisponibilite(1);
			$entityLivre->setImage("/images/719FffADQAL._SL140_.jpg");
			$manager->persist($entityLivre);
			$manager->flush();

			// No country for old men
			$entityFilm = new Film();
			$entityFilm->setId(562169459);
			$entityFilm->setTitre("No country for old men");
			$entityFilm->setRealisateur("Ethan Coen");
			$entityFilm->setISBN("2212110685");
			$entityFilm->SetDuree(new DateInterval('PT2H20M'));
			$entityFilm->setDateDeParution("23/01/2008");
			$entityFilm->setPrix("10.90");
			$entityFilm->setDisponibilite(1);
			$entityFilm->setImage("/images/564651.jpg");
			$manager->persist($entityFilm);
			
			// $entityFilm
			$entityFilm = new Film();
			$entityFilm->setId(56299454);
			$entityFilm->setTitre("Django Unchained");
			$entityFilm->setRealisateur("Quentin Tarentino");
			$entityFilm->setISBN("1234567890");
			$entityFilm->setDuree(new DateInterval('PT2H30M'));
			$entityFilm->setDateDeParution("01/01/2022");
			$entityFilm->setPrix("12.50");
			$entityFilm->setDisponibilite(1);
			$entityFilm->setImage("/images/django.jpg");
			$manager->persist($entityFilm);

			// Interstellar
			$entityFilm = new Film();
			$entityFilm->setId(56299321);
			$entityFilm->setTitre("Interstellar");
			$entityFilm->setRealisateur("Christopher Nolan");
			$entityFilm->setISBN("2345678901");
			$entityFilm->setDuree(new DateInterval('PT2H49M'));
			$entityFilm->setDateDeParution("15/11/2014");
			$entityFilm->setPrix("14.99");
			$entityFilm->setDisponibilite(1);
			$entityFilm->setImage("/images/interstellar.jpg");
			$manager->persist($entityFilm);

			// Blade Runner
			$entityFilm = new Film();
			$entityFilm->setId(56299526);
			$entityFilm->setTitre("Blade Runner");
			$entityFilm->setRealisateur("Ridley Scott");
			$entityFilm->setISBN("3456789012");
			$entityFilm->setDuree(new DateInterval('PT1H57M'));
			$entityFilm->setDateDeParution("25/06/1982");
			$entityFilm->setPrix("9.99");
			$entityFilm->setDisponibilite(1);
			$entityFilm->setImage("/images/blade_runner.jpg");
			$manager->persist($entityFilm);

			// Blade Runner 2049
			$entityFilm = new Film();
			$entityFilm->setId(56297412);
			$entityFilm->setTitre("Blade Runner 2049");
			$entityFilm->setRealisateur("Denis Villeneuve");
			$entityFilm->setISBN("4567890123");
			$entityFilm->setDuree(new DateInterval('PT2H44M'));
			$entityFilm->setDateDeParution("06/10/2017");
			$entityFilm->setPrix("16.99");
			$entityFilm->setDisponibilite(1);
			$entityFilm->setImage("/images/blade_runner_2049.jpg");
			$manager->persist($entityFilm);

			// Inception
			$entityFilm = new Film();
			$entityFilm->setId(562994515);
			$entityFilm->setTitre("Inception");
			$entityFilm->setRealisateur("Christopher Nolan");
			$entityFilm->setISBN("6789012345");
			$entityFilm->setDuree(new DateInterval('PT2H28M'));
			$entityFilm->setDateDeParution("08/07/2010");
			$entityFilm->setPrix("13.75");
			$entityFilm->setDisponibilite(1);
			$entityFilm->setImage("/images/inception.jpg");
			$manager->persist($entityFilm);

			$manager->flush();
		}
    }
}
