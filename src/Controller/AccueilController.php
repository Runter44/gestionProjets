<?php

namespace App\Controller;

use App\Entity\Projet;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controleur de gestion de la page d'accueil
 * @author Simon Jaunet <sjaunet@umanit.fr>
 */
class AccueilController extends Controller
{
    /**
     * @Route("/", name="accueil")
     * Fonction principale d'affichage de la page d'accueil
     * @return Template La vue de la page d'accueil
     */
    public function index()
    {
        $allProjets = $this->getDoctrine()->getRepository(Projet::class)->findAllOrderedByDate();

        return $this->render('accueil/accueil.html.twig', [
            'projets' => $allProjets,
        ]);
    }
}
