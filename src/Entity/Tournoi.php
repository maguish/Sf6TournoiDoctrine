<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournoiRepository::class)]
class Tournoi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /*
    * Lors de la soumission avec la route "/tournoi/saisirTnoi", une erreur apparaît car 
    * le contrôleur qu’on n’a pas modifié tente de rendre persistant le tournoi alors que
    *l’événement qu’on crèe simultanément n’existe pas dans la BD (pas de clé étrangère ev_id).
    *
    * Pour la résoudre, on ajoute une règle de cascade sur l’association reliant tournoi 
    à événement afin que l’événement soit rendu persistant avant le tournoi qui en dépend
    */
    // #[ORM\ManyToOne(inversedBy: 'tournois')]
    #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'tournois', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evenement $ev = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEv(): ?Evenement
    {
        return $this->ev;
    }

    public function setEv(?Evenement $ev): static
    {
        $this->ev = $ev;

        return $this;
    }
}
