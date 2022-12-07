<?php

namespace App\Entity;

use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprietaireRepository::class)]
class Proprietaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\ManyToMany(targetEntity: Chaton::class, inversedBy: 'id_proprietaire')]
    private Collection $id_chaton;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    public function __construct()
    {
        $this->id_chaton = new ArrayCollection();
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

    /**
     * @return Collection<int, Chaton>
     */
    public function getIdChaton(): Collection
    {
        return $this->id_chaton;
    }

    public function addIdChaton(Chaton $idChaton): self
    {
        if (!$this->id_chaton->contains($idChaton)) {
            $this->id_chaton->add($idChaton);
        }

        return $this;
    }

    public function removeIdChaton(Chaton $idChaton): self
    {
        $this->id_chaton->removeElement($idChaton);

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
}
