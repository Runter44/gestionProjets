<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeTacheRepository")
 */
class TypeTache
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Tache", inversedBy="typeTaches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tache;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeProjet", inversedBy="typeTaches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeProjet;

    public function getTache(): ?Tache
    {
        return $this->tache;
    }

    public function setTache(?Tache $tache): self
    {
        $this->tache = $tache;

        return $this;
    }

    public function getTypeProjet(): ?TypeProjet
    {
        return $this->typeProjet;
    }

    public function setTypeProjet(?TypeProjet $typeProjet): self
    {
        $this->typeProjet = $typeProjet;

        return $this;
    }
}
