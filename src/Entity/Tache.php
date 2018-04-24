<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TacheRepository")
 */
class Tache
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="taches")
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TacheProjet", mappedBy="tache")
     */
    private $tacheProjets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TypeTache", mappedBy="tache")
     */
    private $tacheTypes;

    public function __construct()
    {
        $this->tacheProjets = new ArrayCollection();
        $this->tacheTypes = new ArrayCollection();
    }

    public function getId()
    {
      return $this->id;
    }

    public function getName()
    {
      return $this->name;
    }

    public function setName($nom)
    {
      $this->name = $nom;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|TacheProjet[]
     */
    public function getTacheProjets(): Collection
    {
        return $this->tacheProjets;
    }

    public function addTacheProjet(TacheProjet $tacheProjet): self
    {
        if (!$this->tacheProjets->contains($tacheProjet)) {
            $this->tacheProjets[] = $tacheProjet;
            $tacheProjet->setTache($this);
        }

        return $this;
    }

    public function removeTacheProjet(TacheProjet $tacheProjet): self
    {
        if ($this->tacheProjets->contains($tacheProjet)) {
            $this->tacheProjets->removeElement($tacheProjet);
            // set the owning side to null (unless already changed)
            if ($tacheProjet->getTache() === $this) {
                $tacheProjet->setTache(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TypeTache[]
     */
    public function getTypeTaches(): Collection
    {
        return $this->tacheTypes;
    }

    public function addTypeTache(TypeTache $tacheType): self
    {
        if (!$this->tacheTypes->contains($tacheType)) {
            $this->tacheTypes[] = $tacheType;
            $tacheType->setTache($this);
        }

        return $this;
    }

    public function removeTypeTache(TypeTache $tacheType): self
    {
        if ($this->tacheTypes->contains($tacheType)) {
            $this->tacheTypes->removeElement($tacheType);
            // set the owning side to null (unless already changed)
            if ($tacheType->getTache() === $this) {
                $tacheType->setTache(null);
            }
        }

        return $this;
    }
}
