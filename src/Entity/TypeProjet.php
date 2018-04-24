<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjetTypeRepository")
 * @ORM\Table(name="projet_type")
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TypeTache", mappedBy="projet")
     */
    private $typeTaches;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->tacheTypes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $tacheType->setProjet($this);
        }

        return $this;
    }

    public function removeTypeTache(TypeTache $tacheType): self
    {
        if ($this->tacheTypes->contains($tacheType)) {
            $this->tacheTypes->removeElement($tacheType);
            // set the owning side to null (unless already changed)
            if ($tacheType->getProjet() === $this) {
                $tacheType->setProjet(null);
            }
        }

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
}
