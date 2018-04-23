<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TacheProjetRepository")
 */
class TacheProjet
{


    /**
     * @ORM\Column(type="boolean")
     */
    private $termine;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Tache", inversedBy="tacheProjets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tache;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Projet", inversedBy="tacheProjets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

    public function setTermine($value)
    {
      $this->termine = $value;
    }

    public function getTermine()
    {
      return $this->termine;
    }

    public function getTache(): ?Tache
    {
        return $this->tache;
    }

    public function setTache(?Tache $tache): self
    {
        $this->tache = $tache;

        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }
}
