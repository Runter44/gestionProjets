<?php

namespace App\Controller;

use App\Entity\Projet;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        $allProjets = $this->getDoctrine()->getRepository(Projet::class)->findAllOrderedByDate();

        return $this->render('accueil/accueil.html.twig', [
            'projets' => $allProjets,
        ]);
    }
}
