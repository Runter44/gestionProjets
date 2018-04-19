<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TacheProjetRepository")
 */
class TacheProjet
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $idTache;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $idProjet;

    /**
     * @ORM\Column(type="boolean")
     */
    private $termine;

    public function setIdTache($value)
    {
      $this->idTache = $value;
    }

    public function setIdProjet($value)
    {
      $this->idProjet = $value;
    }

    public function setTermine($value)
    {
      $this->termine = $value;
    }

    public function getIdTache()
    {
      return $this->idTache;
    }

    public function getIdProjet()
    {
      return $this->idProjet;
    }

    public function getTermine()
    {
      return $this->termine;
    }
}
