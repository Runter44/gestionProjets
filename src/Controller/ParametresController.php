<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Categorie;
use App\Entity\Tache;
use App\Entity\TacheProjet;
use App\Entity\TypeTache;
use App\Entity\TypeProjet;
use App\Form\CategorieType;
use App\Form\TacheType;
use App\Form\ProjetType;
use App\Form\TypeTacheType;
use App\Form\TypeProjetType;
use App\Utils\Slugger;
use Doctrine\Common\Collections\ArrayCollection;
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
        $typesProjet = $this->getDoctrine()->getRepository(TypeProjet::class)->findAll();

        return $this->render('parametres/projets/parametresProjets.html.twig', [
            'projets' => $projets,
            'tachesProjets' => $tachesProjets,
            'typesProjet' => $typesProjet,
        ]);
    }

    /**
     * @Route("/parametres/projets/nouveau/", name="nouveauProjet")
     */
    public function nouveauProjet(Request $request, Slugger $slugger)
    {
        $taches = $this->getDoctrine()->getRepository(Tache::class)->findAll();
        $typesProjet = $this->getDoctrine()->getRepository(TypeProjet::class)->findAll();

        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
              $entityManager = $this->getDoctrine()->getManager();
              $tempTaches = array();
              foreach ($projet->getTacheProjets() as $tache) {
                  $tache->setTermine(false);
                  if (in_array($tache, $tempTaches)) {
                    $projet->removeTacheProjet($tache);
                  } else {
                    array_push($tempTaches, $tache);
                    $entityManager->persist($tache);
                  }
              }

              $projet->setSlug($slugger->genererSlug($projet->getName()));

              if (count($projet->getTacheProjets()) > 0) { // on enregistre uniquement si il y a des tâches ajoutées
                $entityManager->persist($projet);
                $entityManager->flush();
              }
              return $this->redirectToRoute('parametresProjets');
        }

        return $this->render('parametres/projets/nouveauProjet.html.twig', [
            'taches' => $taches,
            'typesProjet' => $typesProjet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parametres/projets/nouveau/{type}/", name="nouveauProjetImport")
     */
    public function nouveauProjetImport($type, Request $request, Slugger $slugger)
    {
        $taches = $this->getDoctrine()->getRepository(Tache::class)->findAll();
        $typeSelectionne = $this->getDoctrine()->getRepository(TypeProjet::class)->findOneBy(["slug" => $type]);

        if ($typeSelectionne == null) {
            return $this->redirectToRoute('nouveauProjet');
        }

        $projet = new Projet();
        foreach ($typeSelectionne->getTypeTaches() as $typeTache) {
            $tacheProjet = new TacheProjet();
            $tacheProjet->setProjet($projet);
            $tacheProjet->setTache($typeTache->getTache());
            $projet->addTacheProjet($tacheProjet);
        }
        $form = $this->createForm(ProjetType::class, $projet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
              $entityManager = $this->getDoctrine()->getManager();
              $tempTaches = array();
              foreach ($projet->getTacheProjets() as $tache) {
                  $tache->setTermine(false);
                  if (in_array($tache, $tempTaches)) {
                    $projet->removeTacheProjet($tache);
                  } else {
                    array_push($tempTaches, $tache);
                    $entityManager->persist($tache);
                  }
              }

              $projet->setSlug($slugger->genererSlug($projet->getName()));

              if (count($projet->getTacheProjets()) > 0) { // on enregistre uniquement si il y a des tâches ajoutées
                $entityManager->persist($projet);
                $entityManager->flush();
              }
              return $this->redirectToRoute('parametresProjets');
        }

        return $this->render('parametres/projets/nouveauProjet.html.twig', [
            'taches' => $taches,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parametres/projets/modifier/{slug}/", name="modifierProjet")
     */
    public function modifierProjet($slug, Request $request)
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->findOneBy([
          'slug' => $slug,
        ]);

        if ($projet == null) {
            return $this->redirectToRoute('parametresProjets');
        }

        $originalTaches = new ArrayCollection();

        foreach ($projet->getTacheProjets() as $tac) {
            $originalTaches->add($tac);
        }

        $form = $this->createForm(ProjetType::class, $projet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $tempTaches = array();

            // on supprime les taches enlevées
            foreach ($originalTaches as $tache) {
                if ($projet->getTacheProjets()->contains($tache) == false) {
                    $entityManager->remove($tache);
                }
            }

            // on ajoute les taches ajoutées
            foreach ($projet->getTacheProjets() as $projetTache) {
                if ($originalTaches->contains($projetTache) == false) {
                    $projetTache->setTermine(false);
                    if (in_array($projetTache, $tempTaches)) {
                      $projet->removeTacheProjet($projetTache);
                    } else {
                      array_push($tempTaches, $projetTache);
                      $entityManager->persist($projetTache);
                    }
                }
            }

            if (count($projet->getTacheProjets()) > 0) { // on enregistre uniquement si il y a des tâches ajoutées
              $entityManager->persist($projet);
              $entityManager->flush();
            }
            return $this->redirectToRoute('parametresProjets');

        }

        return $this->render('parametres/projets/modifierProjet.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
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
            'types_projets' => $this->getDoctrine()->getRepository(TypeProjet::class)->findAll(),
        ]);
    }

    /**
     * @Route("/parametres/types-projets/nouveau/", name="nouveauType")
     */
    public function nouveauType(Request $request, Slugger $slugger)
    {
        $typeProjet = new TypeProjet();
        $form = $this->createForm(TypeProjetType::class, $typeProjet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
              $entityManager = $this->getDoctrine()->getManager();
              $tempTaches = array();
              foreach ($typeProjet->getTypeTaches() as $tache) {
                  if (in_array($tache, $tempTaches)) {
                    $typeProjet->removeTypeTache($tache);
                  } else {
                    array_push($tempTaches, $tache);
                    $entityManager->persist($tache);
                  }
              }

              $typeProjet->setSlug($slugger->genererSlug($typeProjet->getNom()));

              if (count($typeProjet->getTypeTaches()) > 0) { // on enregistre uniquement si il y a des tâches ajoutées
                $entityManager->persist($typeProjet);
                $entityManager->flush();
              }
              return $this->redirectToRoute('parametresTypes');
        }

        return $this->render('parametres/types/nouveauType.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parametres/types-projets/modifier/{name}/", name="modifierType")
     */
    public function modifierType($name, Request $request)
    {
        $typeProjet = $this->getDoctrine()->getRepository(TypeProjet::class)->findOneBy(["slug" => $name,]);

        if ($typeProjet == null) {
            return $this->redirectToRoute('parametresTypes');
        }

        $originalTaches = new ArrayCollection();

        foreach ($typeProjet->getTypeTaches() as $tac) {
            $originalTaches->add($tac);
        }

        $form = $this->createForm(TypeProjetType::class, $typeProjet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $tempTaches = array();

            // on supprime les taches enlevées
            foreach ($originalTaches as $tache) {
                if ($typeProjet->getTypeTaches()->contains($tache) == false) {
                    $entityManager->remove($tache);
                }
            }

            // on ajoute les taches ajoutées
            foreach ($typeProjet->getTypeTaches() as $typeTache) {
                if ($originalTaches->contains($typeTache) == false) {
                    if (in_array($typeTache, $tempTaches)) {
                      $typeProjet->removeTacheProjet($typeTache);
                    } else {
                      array_push($tempTaches, $typeTache);
                      $entityManager->persist($typeTache);
                    }
                }
            }

            if (count($typeProjet->getTypeTaches()) > 0) { // on enregistre uniquement si il y a des tâches ajoutées
              $entityManager->persist($typeProjet);
              $entityManager->flush();
            }
            return $this->redirectToRoute('parametresTypes');
        }

        return $this->render('parametres/types/modifierType.html.twig', [
            'form' => $form->createView(),
            'typeProjet' => $typeProjet,
        ]);
    }

    /**
     * @Route("/parametres/types-projets/supprimer/{id}/", name="supprimerTypeProjet")
     */
    public function supprimerTypeProjet($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $typeProjet = $this->getDoctrine()->getRepository(TypeProjet::class)->find($id);

        foreach ($typeProjet->getTypeTaches() as $tache) {
          $entityManager->remove($tache);
        }

        $entityManager->remove($typeProjet);
        $entityManager->flush();

        return $this->redirectToRoute("parametresTypes");
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
