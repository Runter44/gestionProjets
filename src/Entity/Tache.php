<?php

namespace App\Entity;

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
     * @ORM\Column(type="integer")
     */
    private $idCategorie;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    public function getId()
    {
      return $this->id;
    }

    public function setIdCategorie($idCateg)
    {
      $this->idCategorie = $idCateg;
    }

    public function getIdCategorie()
    {
      return $this->idCategorie;
    }

    public function getName()
    {
      return $this->name;
    }

    public function setName($nom)
    {
      $this->name = $nom;
    }
}
