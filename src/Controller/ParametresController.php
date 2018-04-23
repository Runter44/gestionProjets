<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Categorie;
use App\Entity\Tache;
use App\Entity\TacheProjet;
use App\Form\CategorieType;
use App\Form\TacheType;
use App\Form\ProjetType;
use App\Utils\Slugger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ParametresController extends Controller
{

    /* PARAMETRES DES PROJETS */

    /**
     * @Route("/parametres/projets/", name="parametresProjets")
     */
    public function projets()
    {
        $projets = $this->getDoctrine()->getRepository(Projet::class)->findAll();
        $tachesProjets = $this->getDoctrine()->getRepository(TacheProjet::class)->findAll();

        return $this->render('parametres/projets/parametresProjets.html.twig', [
            'projets' => $projets,
            'tachesProjets' => $tachesProjets,
        ]);
    }

    /**
     * @Route("/parametres/projets/nouveau/", name="nouveauProjet")
     */
    public function nouveauProjet(Request $request, Slugger $slugger)
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $taches = $this->getDoctrine()->getRepository(Tache::class)->findAll();

        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $projetTest = $this->getDoctrine()->getRepository(Projet::class)->findOneBy(
              ['name' => $form->get("name")->getData()]
            );

            if ($projetTest == null && $_POST["nbTachesProjet"] > 0) {
              foreach ($taches as $tache) {
                if (isset($_POST["tache".$tache->getId()])) {
                  $tacheProjet = new TacheProjet();
                  $tacheProjet->setTache($tache);
                  $tacheProjet->setProjet($projet);
                  $tacheProjet->setTermine(false);

                  $projet->addTacheProjet($tacheProjet);

                  $entityManager->persist($tacheProjet);
                }
              }

              $projet->setSlug($slugger->genererSlug($projet->getName()));

              $entityManager->persist($projet);
              $entityManager->flush();
              return $this->redirectToRoute('parametresProjets');
            }
        }

        return $this->render('parametres/projets/nouveauProjet.html.twig', [
            'categories' => $categories,
            'taches' => $taches,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parametres/projets/modifier/{slug}/", name="modifierProjet")
     */
    public function modifierProjet($slug)
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->findOneBy([
          'slug' => $slug,
        ]);
        return $this->render('parametres/projets/modifierProjet.html.twig', [
            'projet' => $projet,
        ]);
    }

    /**
     * @Route("/parametres/projets/supprimer/{id}/", name="supprimerProjet")
     */
    public function supprimerProjet($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $projet = $this->getDoctrine()->getRepository(Projet::class)->find($id);

        $tachesProjet = $this->getDoctrine()->getRepository(TacheProjet::class)->findBy(
          ['projet' => $projet]
        );

        foreach ($tachesProjet as $tache) {
          $entityManager->remove($tache);
        }

        $entityManager->remove($projet);
        $entityManager->flush();

        return $this->redirectToRoute("parametresProjets");
    }







    /* PARAMETRES DES TYPES DE PROJETS */

    /**
     * @Route("/parametres/types-projets/", name="parametresTypes")
     */
    public function types()
    {
        return $this->render('parametres/types/parametresTypes.html.twig', [
            'controller_name' => 'ParametresController',
        ]);
    }

    /**
     * @Route("/parametres/types-projets/nouveau/", name="nouveauType")
     */
    public function nouveauType()
    {
        return $this->render('parametres/types/nouveauType.html.twig', [
            'controller_name' => 'ParametresController',
        ]);
    }

    /**
     * @Route("/parametres/types-projets/modifier/{name}/", name="modifierType")
     */
    public function modifierType($name)
    {
        return $this->render('parametres/types/modifierType.html.twig', [
            'type' => urldecode($name),
        ]);
    }







    /* PARAMETRES DES TACHES ET CATEGORIES */

    /**
     * @Route("/parametres/taches/", name="parametresTaches")
     */
    public function taches(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $taches = $this->getDoctrine()->getRepository(Tache::class)->findAll();

        $categorie = new Categorie();
        $tache = new Tache();

        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        $formTache = $this->createForm(TacheType::class, $tache);

        $formCategorie->handleRequest($request);

        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('parametresTaches');
        }

        $formTache->handleRequest($request);

        if ($formTache->isSubmitted() && $formTache->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tache);
            $entityManager->flush();

            return $this->redirectToRoute('parametresTaches');
        }

        return $this->render('parametres/taches/parametresTaches.html.twig', [
            'categories' => $categories,
            'taches' => $taches,
            'formCategorie' => $formCategorie->createView(),
            'formTache' => $formTache->createView(),
        ]);
    }

    /**
     * @Route("/parametres/taches/supprimer-tache/{id}/", name="supprimerTache")
     */
    public function supprimerTache($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tache = $this->getDoctrine()->getRepository(Tache::class)->find($id);

        $tacheProjets = $this->getDoctrine()->getRepository(TacheProjet::class)->findBy(
          ['tache' => $tache]
        );

        foreach ($tacheProjets as $tacheProj) {
          $entityManager->remove($tacheProj);
          $entityManager->flush();
        }

        $entityManager->remove($tache);
        $entityManager->flush();
        return $this->redirect("/parametres/taches/");
    }

    /**
     * @Route("/parametres/taches/supprimer-categorie/{id}/", name="supprimerCategorie")
     */
    public function supprimerCategorie($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $tachesCategorie = $this->getDoctrine()->getRepository(Tache::class)->findBy(
          ['categorie' => $categorie]
        );

        foreach ($tachesCategorie as $tache) {
          $entityManager->remove($tache);
        }

        $entityManager->remove($categorie);

        $entityManager->flush();

        return $this->redirect("/parametres/taches/");
    }




    /* REDIRIGER LA PAGE PARAMETRES */

    /**
     * @Route("/parametres/", name="parametres")
     */
    public function params()
    {
        return $this->redirect("/", 308);
    }
}
