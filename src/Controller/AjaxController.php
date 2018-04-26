<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\TypeProjet;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{
    /**
     * @Route("/ajax/nom-projet-existe/", name="nomProjetExiste")
     */
    public function nomProjetExiste(Request $request)
    {
        if ($request->request->get("nomProjet")) {
          $projet = $this->getDoctrine()->getRepository(Projet::class)->findOneBy(
            ['name' => $request->request->get("nomProjet")]
          );
          if ($projet !== null) {
            return new Response($projet->getName());
          } else {
            return new Response("");
          }
        }
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/ajax/nom-categorie-existe/", name="nomCategorieExiste")
     */
    public function nomCategorieExiste(Request $request)
    {
        if ($request->request->get("nomCategorie")) {
          $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy(
            ['name' => $request->request->get("nomCategorie")]
          );
          if ($categorie !== null) {
            return new Response($categorie->getName());
          } else {
            return new Response("");
          }
        }
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/ajax/nom-type-existe/", name="nomTypeExiste")
     */
    public function nomTypeExiste(Request $request)
    {
        if ($request->request->get("nomTypeProjet")) {
          $typeProjet = $this->getDoctrine()->getRepository(TypeProjet::class)->findOneBy(
            ['nom' => $request->request->get("nomTypeProjet")]
          );
          if ($typeProjet !== null) {
            return new Response($typeProjet->getNom());
          } else {
            return new Response("");
          }
        }
        return $this->redirectToRoute('accueil');
    }
}
