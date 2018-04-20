<?php

namespace App\Controller;

use App\Entity\Projet;
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
}
