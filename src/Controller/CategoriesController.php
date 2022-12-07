<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieSupprimerType;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/', name: 'app_categories')]
    public function index(ManagerRegistry $doctrine): Response
    {

        //On va aller chercher les catégories dans la BDD, on a besoin d'un repository pour ça
        $repo = $doctrine->getRepository(Categorie::class);
        $categories = $repo->findAll(); //Select * transformé en liste de Categorie

        return $this->render('categories/index.html.twig', [
            'categories'=>$categories
        ]);
    }

    #[Route('/categorie/ajouter', name: 'app_categories_ajouter')]
    public  function  ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        //Créer le formulaire -> d'abord une catégorie vide
        $categorie = new Categorie();
        //Avec ça on crée le formulaire
        $form = $this->createForm(CategorieType::class, $categorie);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //L'objet catégorie est rempli, on va utiliser l'entity manager de doctrine
            $em = $doctrine->getManager();
            //On lui dit qu'on veut mettre la catégorie dans la table
            $em->persist($categorie);

            //On génère l'appel SQL (Insert)
            $em->flush();

            //On revient à l'accueil
            return  $this->redirectToRoute("app_categories");
        }

        return $this->render('categories/ajouter.html.twig', [
            "formulaire"=>$form->CreateView()
        ]);
    }

    #[Route('/categorie/modifier/{id}', name: 'app_categories_modifier')]
    public  function  modifier(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        //Créer le formulaire (comme ajouter mais avec une catégorie existante)
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        if (!$categorie){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($categorie);

            $em->flush();

            return  $this->redirectToRoute("app_categories");
        }

        return $this->render('categories/modifier.html.twig', [
            "id"=>$id,
            "categorie"=>$categorie,
            "formulaire"=>$form->CreateView()
        ]);
    }

    #[Route('/categorie/supprimer/{id}', name: 'app_categories_supprimer')]
    public  function  supprimer(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        //Créer le formulaire (comme ajouter mais avec une catégorie existante)
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        if (!$categorie){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        $form = $this->createForm(CategorieSupprimerType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->remove($categorie);

            $em->flush();

            return  $this->redirectToRoute("app_categories");
        }

        return $this->render('categories/supprimer.html.twig', [
            "id"=>$id,
            "categorie"=>$categorie,
            "formulaire"=>$form->CreateView()
        ]);
    }
}
