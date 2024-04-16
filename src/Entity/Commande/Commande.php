<?php

namespace App\Entity\Commande;

use App\Entity\Panier\Panier;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Commande {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private ?string $nom = null;

    #[ORM\Column(type: 'string')]
    private ?string $prenom = null;

    #[ORM\Column(type: 'string')]
    private ?string $email = null;

    #[ORM\Column(type: 'string')]
    private ?string $telephone = null;

    #[ORM\Column(type: 'string')]
    private ?string $adress = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(type: 'float')]
    private ?float $montant = null;

    #[ORM\OneToOne(targetEntity: Panier::class, cascade: ['persist', 'remove'])]
    private ?Panier $panier = null;

    public function __construct ($nom, $prenom, $email, $telephone, $adress, $dateCommande, $montant, $panier) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->adress = $adress;
        $this->dateCommande = $dateCommande;
        $this->montant = $montant;
        $this->panier = $panier;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function getDateCommande(): ?\DateTime
    {
        return $this->dateCommande;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }
}

?>