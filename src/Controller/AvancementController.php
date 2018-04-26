<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Categorie;
use App\Entity\Tache;
use App\Entity\TacheProjet;
use App\Form\AvancementType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AvancementController extends Controller
{
    /**
     * @Route("/avancement/{slug}/", name="voirAvancement")
     */
    public function index($slug, Request $request)
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->findOneBy([
          'slug' => $slug,
        ]);

        if ($projet == null) {
            return $this->redirectToRoute('accueil');
        }

        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        $form = $this->createForm(AvancementType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($projet->getTacheProjets() as $tache) {
                if ($request->get("tache".$tache->getTache()->getId()) != null) {
                    $tache->setTermine(true);
                } else {
                    $tache->setTermine(false);
                }
                $entityManager->persist($tache);
            }

            $dateModif = new \DateTime();
            $dateModif->setTimezone(new \DateTimeZone("Europe/Paris"));
            $projet->setDateModif($dateModif);

            $entityManager->persist($projet);
            $entityManager->flush();
            return $this->redirectToRoute('voirAvancement', ['slug' => $projet->getSlug()]);
        }

        return $this->render('avancement/avancement.html.twig', [
            'projet' => $projet,
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }
}
