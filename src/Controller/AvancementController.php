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
     * @Route("/avancement/{slug}/", name="voirAvancement")
     */
    public function index($slug)
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->findOneBy([
          'slug' => $slug,
        ]);

        if ($projet == null) {
            return $this->redirectToRoute('accueil');
        }

        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        return $this->render('avancement/avancement.html.twig', [
            'projet' => $projet,
            'categories' => $categories,
        ]);
    }
}
