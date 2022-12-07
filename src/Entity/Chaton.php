<?php

namespace App\Entity;

use App\Repository\ChatonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: ChatonRepository::class)]
class Chaton
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    #[ORM\Column]
    private ?bool $Sterile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Photo = null;

    #[ORM\ManyToOne(inversedBy: 'chatons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $Categorie = null;

    #[ORM\ManyToMany(targetEntity: Proprietaire::class, mappedBy: 'id_chaton')]
    private Collection $id_proprietaire;

    public function __construct()
    {
        $this->id_proprietaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }


    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(?string $Photo): self
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function isSterile(): ?bool
    {
        return $this->Sterile;
    }

    public function setSterile(bool $Sterile): self
    {
        $this->Sterile = $Sterile;

        return $this;
    }

    /**
     * @return Collection<int, Proprietaire>
     */
    public function getIdProprietaire(): Collection
    {
        return $this->id_proprietaire;
    }

    public function addIdProprietaire(Proprietaire $idProprietaire): self
    {
        if (!$this->id_proprietaire->contains($idProprietaire)) {
            $this->id_proprietaire->add($idProprietaire);
            $idProprietaire->addIdChaton($this);
        }

        return $this;
    }

    public function removeIdProprietaire(Proprietaire $idProprietaire): self
    {
        if ($this->id_proprietaire->removeElement($idProprietaire)) {
            $idProprietaire->removeIdChaton($this);
        }

        return $this;
    }
}
