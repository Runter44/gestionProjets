<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Categorie;
use App\Entity\Tache;
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

        return $this->render('parametres/projets/parametresProjets.html.twig', [
            'projets' => $projets,
        ]);
    }

    /**
     * @Route("/parametres/projets/nouveau/", name="nouveauProjet")
     */
    public function nouveauProjet()
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $taches = $this->getDoctrine()->getRepository(Tache::class)->findAll();

        return $this->render('parametres/projets/nouveauProjet.html.twig', [
            'categories' => $categories,
            'taches' => $taches,
        ]);
    }

    /**
     * @Route("/parametres/projets/nouveau/enregistrer/", name="saveNouveauProjet")
     */
    public function enregistrerNouveauProjet()
    {
        if (isset($_POST["nomProjet"])) {
          $entityManager = $this->getDoctrine()->getManager();

          $projetTest = $this->getDoctrine()->getRepository(Projet::class)->findOneBy(
            ['name' => $_POST["nomProjet"]]
          );

          if ($projetTest == null) {
            $projet = new Projet();
            $projet->setName($_POST["nomProjet"]);

            $entityManager->persist($projet);

            $entityManager->flush();
          } else {
            return $this->redirect("/parametres/projets/");
          }
        }
        return $this->redirect("/parametres/projets/");
    }

    /**
     * @Route("/parametres/projets/modifier/{name}/", name="modifierProjet")
     */
    public function modifierProjet($name)
    {
        return $this->render('parametres/projets/modifierProjet.html.twig', [
            'projet' => urldecode($name),
        ]);
    }

    /**
     * @Route("/parametres/projets/supprimer/{id}/", name="supprimerProjet")
     */
    public function supprimerProjet($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $projet = $this->getDoctrine()->getRepository(Projet::class)->find($id);
        /*ajouter pour supprimer la tache supprimée des projets*/
        $entityManager->remove($projet);
        $entityManager->flush();

        return $this->redirect("/parametres/projets/");
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
    public function taches()
    {
      $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
      $taches = $this->getDoctrine()->getRepository(Tache::class)->findAll();

        return $this->render('parametres/taches/parametresTaches.html.twig', [
            'categories' => $categories,
            'taches' => $taches,
        ]);
    }

    /**
     * @Route("/parametres/taches/nouvelle-tache/", name="nouvelleTache")
     */
    public function nouvelleTache()
    {
        if (isset($_POST["nomTache"])) {
          $entityManager = $this->getDoctrine()->getManager();

          $tache = new Tache();
          $tache->setName($_POST["nomTache"]);
          $tache->setIdCategorie($_POST["categorieTache"]);

          $entityManager->persist($tache);
          $entityManager->flush();
        }

        return $this->redirect("/parametres/taches/");
    }

    /**
     * @Route("/parametres/taches/supprimer-tache/{id}/", name="supprimerTache")
     */
    public function supprimerTache($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tache = $this->getDoctrine()->getRepository(Tache::class)->find($id);
        /*ajouter pour supprimer la tache supprimée des projets*/
        $entityManager->remove($tache);
        $entityManager->flush();
        return $this->redirect("/parametres/taches/");
    }

    /**
     * @Route("/parametres/taches/nouvelle-categorie/", name="nouvelleCategorie")
     */
    public function nouvelleCategorie()
    {
        if (isset($_POST["nomCategorie"])) {
          $entityManager = $this->getDoctrine()->getManager();

          $categorie = new Categorie();

          $categorie->setName($_POST["nomCategorie"]);

          $entityManager->persist($categorie);

          $entityManager->flush();
        }

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
          ['idCategorie' => $id]
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
