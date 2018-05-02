<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\TypeProjet;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controleur de gestion des requêtes ajax
 * @author Simon Jaunet <sjaunet@umanit.fr>
 */
class AjaxController extends Controller
{
    /**
     * @Route("/ajax/nom-projet-existe/", name="nomProjetExiste")
     * Requête ajax pour vérifier l'existence d'un nom de projet
     * @param Request $request La requête envoyée à l'URL
     * @return Response Le nom du projet s'il existe, une chaîne vide sinon
     */
    public function nomProjetExiste(Request $request)
    {
        if ($request->request->get("nomProjet")) {
            $projet = $this->getDoctrine()->getRepository(Projet::class)->findOneBy(
                ['name' => $request->request->get("nomProjet")]
            );
            if ($projet !== null) {
                return new Response($projet->getName());
            }
            return new Response("");
        }
        return new Response("Mauvaise requête !", 400);
    }

    /**
     * @Route("/ajax/nom-categorie-existe/", name="nomCategorieExiste")
     * Requête ajax pour vérifier l'existence d'une catégorie
     * @param Request $request La requête envoyée à l'URL
     * @return Response Le nom de la catégorie si elle existe, une chaîne vide sinon
     */
    public function nomCategorieExiste(Request $request)
    {
        if ($request->request->get("nomCategorie")) {
            $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy(
                ['name' => $request->request->get("nomCategorie")]
            );
            if ($categorie !== null) {
                return new Response($categorie->getName());
            }
            return new Response("");
        }
        return new Response("Mauvaise requête !", 400);
    }

    /**
     * @Route("/ajax/nom-type-existe/", name="nomTypeExiste")
     * Requête ajax pour vérifier l'existence d'un type de projet
     * @param Request $request La requête envoyée à l'URL
     * @return Response Le nom du type de projet s'il existe, une chaîne vide sinon
     */
    public function nomTypeExiste(Request $request)
    {
        if ($request->request->get("nomTypeProjet")) {
            $typeProjet = $this->getDoctrine()->getRepository(TypeProjet::class)->findOneBy(
                ['nom' => $request->request->get("nomTypeProjet")]
            );
            if ($typeProjet !== null) {
                return new Response($typeProjet->getNom());
            }
            return new Response("");
        }
        return new Response("Mauvaise requête !", 400);
    }
}
