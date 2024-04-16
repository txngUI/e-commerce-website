<?php

namespace App\Entity\Catalogue;


use Doctrine\ORM\Mapping as ORM;

use DateTime;
use DatePeriod;
use DateInterval;

#[ORM\Entity]
class Film extends Article
{
    #[ORM\Column(length: 255,name: 'realisateur')]
    private ?string $realisateur = null;

    #[ORM\Column(length: 255, name: 'isbn')]
    private ?string $ISBN = null;
    
    #[ORM\Column(length: 255, name: 'duree')]
    private ?DateInterval $duree = null;

    #[ORM\Column(length: 255, name: 'date_de_parution')]
    private ?string $dateDeParution = null;

    public function getRealisateur(): ?string
    {
        return $this->realisateur;
    }

    public function setRealisateur(string $realisateur): static
    {
        $this->realisateur = $realisateur;

        return $this;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): static
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getDuree(): ?DateInterval
    {
        return $this->duree;
    }

    public function SetDuree(DateInterval $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateDeParution(): ?string
    {
        return $this->dateDeParution;
    }

    public function setDateDeParution(string $dateDeParution): static
    {
        $this->dateDeParution = $dateDeParution;

        return $this;
    }
}

