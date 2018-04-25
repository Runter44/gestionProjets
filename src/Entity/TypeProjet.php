<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeProjetRepository")
 */
class TypeProjet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TypeTache", mappedBy="typeProjet")
     */
    private $typeTaches;

    public function __construct()
    {
        $this->typeTaches = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|TypeTache[]
     */
    public function getTypeTaches(): Collection
    {
        return $this->typeTaches;
    }

    public function addTypeTach(TypeTache $typeTach): self
    {
        if (!$this->typeTaches->contains($typeTach)) {
            $this->typeTaches[] = $typeTach;
            $typeTach->setTypeProjet($this);
        }

        return $this;
    }

    public function removeTypeTach(TypeTache $typeTach): self
    {
        if ($this->typeTaches->contains($typeTach)) {
            $this->typeTaches->removeElement($typeTach);
            // set the owning side to null (unless already changed)
            if ($typeTach->getTypeProjet() === $this) {
                $typeTach->setTypeProjet(null);
            }
        }

        return $this;
    }
}
