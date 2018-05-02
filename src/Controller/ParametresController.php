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

/**
 * Controleur de gestion des paramètres
 * @author Simon Jaunet <sjaunet@umanit.fr>
 */
class ParametresController extends Controller
{

    /* PARAMETRES DES PROJETS */

    /**
     * @Route("/parametres/projets/", name="parametresProjets")
     * Fonction d'affichage de la page des paramètres des projets
     * @return Template La page des paramètres des projets
     */
    public function projets()
    {
        $projets = $this->getDoctrine()->getRepository(Projet::class)->findAllOrderedByDate();
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
     * Fonction principale de traitement des nouveaux projets
     * @param Request $request La requête envoyée au controleur
     * @param Slugger $slugger L'utilitaire de génération de slug (@see src/Utils/Slugger.php)
     * @return Template Le template de nouveau projet avec éventuellement des messages flash d'erreur
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
            $projetTest = $this->getDoctrine()->getRepository(Projet::class)->findOneBy(["name" => $projet->getName()]);

            if ($projetTest === null) {
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
                $dateModif = new \DateTime();
                $dateModif->setTimezone(new \DateTimeZone("Europe/Paris"));
                $projet->setDateModif($dateModif);

                if (count($projet->getTacheProjets()) > 0) { // on enregistre uniquement si il y a des tâches ajoutées
                    $entityManager->persist($projet);
                    $entityManager->flush();
                    return $this->redirectToRoute('parametresProjets');
                }
                $this->addFlash('error', 'Vous n\'avez ajouté aucune tâche !');
            } else {
                $this->addFlash('error', 'Ce nom de projet est déjà pris !');
            }
        }

        return $this->render('parametres/projets/nouveauProjet.html.twig', [
            'taches' => $taches,
            'typesProjet' => $typesProjet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parametres/projets/nouveau/{type}/", name="nouveauProjetImport")
     * Fonction principale de traitement des nouveaux projets à partir d'un type de projet
     * @param string $type Le slug du type de projet à utiliser
     * @param Request $request La requête envoyée au controleur
     * @param Slugger $slugger L'utilitaire de génération de slug (@see src/Utils/Slugger.php)
     * @return Template Le template de nouveau projet avec éventuellement des messages flash d'erreur
     */
    public function nouveauProjetImport($type, Request $request, Slugger $slugger)
    {
        $taches = $this->getDoctrine()->getRepository(Tache::class)->findAll();
        $typeSelectionne = $this->getDoctrine()->getRepository(TypeProjet::class)->findOneBy(["slug" => $type]);

        if ($typeSelectionne === null) {
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
            $projetTest = $this->getDoctrine()->getRepository(Projet::class)->findOneBy(["name" => $projet->getName()]);

            if ($projetTest === null) {
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
                $dateModif = new \DateTime();
                $dateModif->setTimezone(new \DateTimeZone("Europe/Paris"));
                $projet->setDateModif($dateModif);

                if (count($projet->getTacheProjets()) > 0) { // on enregistre uniquement si il y a des tâches ajoutées
                    $entityManager->persist($projet);
                    $entityManager->flush();
                    return $this->redirectToRoute('parametresProjets');
                }
                $this->addFlash('error', 'Vous n\'avez ajouté aucune tâche !');
            } else {
                $this->addFlash('error', 'Ce nom de projet est déjà pris !');
            }
        }

        return $this->render('parametres/projets/nouveauProjet.html.twig', [
            'taches' => $taches,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parametres/projets/modifier/{slug}/", name="modifierProjet")
     * Fonction principale de traitement des modifications de projets existante
     * @param string $slug Le slug du projet à modifier
     * @param Request $request La requête envoyée au controleur
     * @param Slugger $slugger L'utilitaire de génération de slug (@see src/Utils/Slugger.php)
     * @return Template Le template de modification de projet avec éventuellement des messages flash d'erreur
     */
    public function modifierProjet($slug, Request $request, Slugger $slugger)
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->findOneBy([
          'slug' => $slug,
        ]);

        if ($projet === null) {
            return $this->redirectToRoute('parametresProjets');
        }

        $nomOriginal = $projet->getName();
        $originalTaches = new ArrayCollection();

        foreach ($projet->getTacheProjets() as $tac) {
            $originalTaches->add($tac);
        }

        $form = $this->createForm(ProjetType::class, $projet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $projetTest = $this->getDoctrine()->getRepository(Projet::class)->findOneBy(["name" => $projet->getName()]);

            if ($projetTest === null || $projet->getName() === $nomOriginal) {
                $tempTaches = array();

                // on supprime les taches enlevées
                foreach ($originalTaches as $tache) {
                    if ($projet->getTacheProjets()->contains($tache) === false) {
                        $entityManager->remove($tache);
                    }
                }

                // on ajoute les taches ajoutées
                foreach ($projet->getTacheProjets() as $projetTache) {
                    if ($originalTaches->contains($projetTache) === false) {
                        $projetTache->setTermine(false);
                        if (in_array($projetTache, $tempTaches)) {
                            $projet->removeTacheProjet($projetTache);
                        } else {
                            array_push($tempTaches, $projetTache);
                            $entityManager->persist($projetTache);
                        }
                    }
                }

                $projet->setSlug($slugger->genererSlug($projet->getName()));

                if (count($projet->getTacheProjets()) > 0) { // on enregistre uniquement si il y a des tâches ajoutées
                    $entityManager->persist($projet);
                    $entityManager->flush();
                    return $this->redirectToRoute('parametresProjets');
                }
                $this->addFlash('error', 'Vous n\'avez ajouté aucune tâche !');
            } else {
                $this->addFlash('error', 'Ce nom de projet est déjà pris !');
            }
        }

        return $this->render('parametres/projets/modifierProjet.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parametres/projets/supprimer/{id}/", name="supprimerProjet")
     * Fonction principale de suppression des projets
     * @param integer $id L'id du projet à supprimer
     * @return Redirect Retour à la page des paramètres de projet
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
     * Fonction d'affichage du template des paramètres de type de projets
     * @return Template Le template des paramètres de type de projet
     */
    public function types()
    {
        return $this->render('parametres/types/parametresTypes.html.twig', [
            'types_projets' => $this->getDoctrine()->getRepository(TypeProjet::class)->findAll(),
        ]);
    }

    /**
     * @Route("/parametres/types-projets/nouveau/", name="nouveauType")
     * Fonction principale de traitement des nouveaux types de projets
     * @param Request $request La requête envoyée au controleur
     * @param Slugger $slugger L'utilitaire de génération de slug (@see src/Utils/Slugger.php)
     * @return Template Le template de nouveau type de projet avec éventuellement des messages flash d'erreur
     */
    public function nouveauType(Request $request, Slugger $slugger)
    {
        $typeProjet = new TypeProjet();
        $form = $this->createForm(TypeProjetType::class, $typeProjet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $typeTest = $this->getDoctrine()->getRepository(TypeProjet::class)->findOneBy(["nom" => $typeProjet->getNom()]);

            if ($typeTest === null) {
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
                } else {
                    $this->addFlash('error', 'Vous n\'avez pas ajouté de tâche !');
                }
                return $this->redirectToRoute('parametresTypes');
            } else {
                $this->addFlash('error', 'Ce nom de type de projet est déjà utilisé !');
            }
        }

        return $this->render('parametres/types/nouveauType.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parametres/types-projets/modifier/{name}/", name="modifierType")
     * Fonction principale de traitement des modifications de types de projets
     * @param string $name Le slug du type de projet à modifier
     * @param Request $request La requête envoyée au controleur
     * @return Template Le template de modification de type de projet avec éventuellement des messages flash d'erreur
     */
    public function modifierType($name, Request $request)
    {
        $typeProjet = $this->getDoctrine()->getRepository(TypeProjet::class)->findOneBy(["slug" => $name, ]);

        if ($typeProjet === null) {
            return $this->redirectToRoute('parametresTypes');
        }

        $nomOriginal = $typeProjet->getNom();
        $originalTaches = new ArrayCollection();

        foreach ($typeProjet->getTypeTaches() as $tac) {
            $originalTaches->add($tac);
        }

        $form = $this->createForm(TypeProjetType::class, $typeProjet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $typeTest = $this->getDoctrine()->getRepository(TypeProjet::class)->findOneBy(["nom" => $typeProjet->getNom()]);

            if ($typeTest === null || $typeProjet->getNom() === $nomOriginal) {
                $tempTaches = array();

                // on supprime les taches enlevées
                foreach ($originalTaches as $tache) {
                    if ($typeProjet->getTypeTaches()->contains($tache) === false) {
                        $entityManager->remove($tache);
                    }
                }

                // on ajoute les taches ajoutées
                foreach ($typeProjet->getTypeTaches() as $typeTache) {
                    if ($originalTaches->contains($typeTache) === false) {
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
            } else {
                $this->addFlash('error', 'Ce nom de type de projet est déjà utilisé !');
            }
        }

        return $this->render('parametres/types/modifierType.html.twig', [
            'form' => $form->createView(),
            'typeProjet' => $typeProjet,
        ]);
    }

    /**
     * @Route("/parametres/types-projets/supprimer/{id}/", name="supprimerTypeProjet")
     * Fonction principale de traitement des nouveaux projets
     * @param integer $id L'id du type de projet à supprimer
     * @return Redirect Retour vers les paramètres de type de projet
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
     * Fonction principale des taches
     * @param Request $request La requête envoyée au controleur
     * @return Template Le template des paramètres des taches avec éventuellement des messages flash d'erreur
     */
    public function taches(Request $request)
    {
        $taches = $this->getDoctrine()->getRepository(Tache::class)->findAll();
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $tache = new Tache();

        $formTache = $this->createForm(TacheType::class, $tache);

        $formTache->handleRequest($request);

        if ($formTache->isSubmitted() && $formTache->isValid()) {
            $tacheTest = $this->getDoctrine()->getRepository(Tache::class)->findOneBy(["name" => $tache->getName()]);

            if ($tacheTest === null) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($tache);
                $entityManager->flush();
            } else {
                $this->addFlash('error', 'Cette tâche existe déjà !');
            }

            return $this->redirectToRoute('parametresTaches');
        }

        return $this->render('parametres/taches/parametresTaches.html.twig', [
            'categories' => $categories,
            'taches' => $taches,
            'formTache' => $formTache->createView(),
        ]);
    }

    /**
     * @Route("/parametres/taches/modifier/{id}/", name="modifierTache")
     * Fonction principale de modification des taches
     * @param integer $id L'ID de la tache à modifier
     * @param Request $request La requête envoyée au controleur
     * @return Template Le template de modification des taches avec éventuellement des messages flash d'erreur
     */
    public function modifierTache($id, Request $request)
    {
        $tache = $this->getDoctrine()->getRepository(Tache::class)->find($id);
        $form = $this->createForm(TacheType::class, $tache);

        $nomOriginal = $tache->getName();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tacheTest = $this->getDoctrine()->getRepository(Tache::class)->findOneBy(["name" => $tache->getName()]);

            if ($tacheTest === null || $tache->getName() === $nomOriginal) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($tache);
                $entityManager->flush();
            } else {
                $this->addFlash('error', 'Cette tâche existe déjà !');
                return $this->redirectToRoute('modifierTache', ["id" => $id]);
            }

            return $this->redirectToRoute('parametresTaches');
        }

        return $this->render('parametres/taches/modifierTache.html.twig', [
            'form' => $form->createView(),
            'tache' => $tache,
        ]);
    }

    /**
     * @Route("/parametres/categories/", name="parametresCategories")
     * Fonction principale des categories
     * @param Request $request La requête envoyée au controleur
     * @return Template Le template des paramètres des categories avec éventuellement des messages flash d'erreur
     */
    public function categories(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $categorie = new Categorie();

        $formCategorie = $this->createForm(CategorieType::class, $categorie);

        $formCategorie->handleRequest($request);

        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            $categorieTest = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy(["name" => $formCategorie->get('name')->getData()]);

            if ($categorieTest === null) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($categorie);
                $entityManager->flush();
            } else {
                $this->addFlash('error', 'Cette catégorie existe déjà !');
            }

            return $this->redirectToRoute('parametresCategories');
        }

        return $this->render('parametres/taches/parametresCategories.html.twig', [
            'categories' => $categories,
            'formCategorie' => $formCategorie->createView(),
        ]);
    }

    /**
     * @Route("/parametres/categories/modifier/{id}/", name="modifierCategorie")
     * Fonction principale de modification des categories
     * @param integer $id L'ID de la categorie à modifier
     * @param Request $request La requête envoyée au controleur
     * @return Template Le template de modification des categories avec éventuellement des messages flash d'erreur
     */
    public function modifierCategorie($id, Request $request)
    {
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $form = $this->createForm(CategorieType::class, $categorie);

        $nomOriginal = $categorie->getName();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categories = $this->getDoctrine()->getRepository(Categorie::class)->findBy(["name" => $form->get('name')->getData()]);

            if (count($categories) === 0 || $nomOriginal === $categorie->getName()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($categorie);
                $entityManager->flush();
            } else {
                $this->addFlash('error', 'Cette catégorie existe déjà !');
                return $this->redirectToRoute('modifierCategorie', ['id' => $id]);
            }
            return $this->redirectToRoute('parametresCategories');
        }

        return $this->render('parametres/taches/modifierCategorie.html.twig', [
            'form' => $form->createView(),
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/parametres/taches/supprimer-tache/{id}/", name="supprimerTache")
     * Fonction de suppression d'une tâche
     * @param integer $id ID de la tache à supprimer
     * @return Redirect Retour à la page de paramètres des taches
     */
    public function supprimerTache($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tache = $this->getDoctrine()->getRepository(Tache::class)->find($id);

        $tacheProjets = $this->getDoctrine()->getRepository(TacheProjet::class)->findBy(
          ['tache' => $tache]
        );
        $typeTache = $this->getDoctrine()->getRepository(TypeTache::class)->findBy(
          ['tache' => $tache]
        );

        foreach ($tacheProjets as $tacheProj) {
            $entityManager->remove($tacheProj);
        }

        foreach ($typeTache as $typeTache) {
            $entityManager->remove($typeTache);
        }

        $entityManager->remove($tache);
        $entityManager->flush();
        return $this->redirectToRoute('parametresTaches');
    }

    /**
     * @Route("/parametres/taches/supprimer-categorie/{id}/", name="supprimerCategorie")
     * Fonction de suppression d'une categorie
     * @param integer $id ID de la categorie à supprimer
     * @return Redirect Retour à la page de paramètres des categories
     */
    public function supprimerCategorie($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $tachesCategorie = $this->getDoctrine()->getRepository(Tache::class)->findBy(
          ['categorie' => $categorie]
        );

        foreach ($tachesCategorie as $tache) {
            $tacheProjets = $this->getDoctrine()->getRepository(TacheProjet::class)->findBy(
              ['tache' => $tache]
            );
            $typeTache = $this->getDoctrine()->getRepository(TypeTache::class)->findBy(
              ['tache' => $tache]
            );

            foreach ($tacheProjets as $tacheProj) {
                $entityManager->remove($tacheProj);
            }

            foreach ($typeTache as $typeTache) {
                $entityManager->remove($typeTache);
            }

            $entityManager->remove($tache);
        }

        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('parametresCategories');
    }




    /* REDIRIGER LA PAGE PARAMETRES */

    /**
     * @Route("/parametres/", name="parametres")
     * @return Redirect Retour à la page d'accueil
     */
    public function params()
    {
        return $this->redirect("/", 308);
    }
}
