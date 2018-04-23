<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjetRepository")
 */
class Projet
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
     * @ORM\OneToMany(targetEntity="App\Entity\TacheProjet", mappedBy="projet")
     */
    private $tacheProjets;

    public function __construct()
    {
        $this->tacheProjets = new ArrayCollection();
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
            $tacheProjet->setProjet($this);
        }

        return $this;
    }

    public function removeTacheProjet(TacheProjet $tacheProjet): self
    {
        if ($this->tacheProjets->contains($tacheProjet)) {
            $this->tacheProjets->removeElement($tacheProjet);
            // set the owning side to null (unless already changed)
            if ($tacheProjet->getProjet() === $this) {
                $tacheProjet->setProjet(null);
            }
        }

        return $this;
    }
}
