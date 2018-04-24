<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TacheTypeRepository")
 * @ORM\Table(name="tache_type")
 */
class TypeTache
{

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Tache", inversedBy="tacheTypes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tache;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeProjet", inversedBy="typeTaches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

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
        return $this->projet;
    }

    public function setTypeProjet(?TypeProjet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }
}
