<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Categorie;
use App\Entity\Tache;
use App\Entity\TacheProjet;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AvancementController extends Controller
{
    /**
     * @Route("/avancement/{idProjet}/", name="voirAvancement")
     */
    public function index($idProjet)
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->find($idProjet);
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $taches = $this->getDoctrine()->getRepository(Tache::class)->findAll();
        $tachesProjet = $this->getDoctrine()->getRepository(TacheProjet::class)->findBy(
          ['idProjet' => $idProjet]
        );

        return $this->render('avancement/avancement.html.twig', [
            'projet' => $projet,
            'categories' => $categories,
            'taches' => $taches,
            'tachesProjet' => $tachesProjet,
        ]);
    }
}
