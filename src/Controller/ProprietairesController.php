<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Entity\Proprietaire;
use App\Form\ProprietaireSupprimerType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProprietairesController extends AbstractController
{
    #[Route('/proprietaires', name: 'app_proprietaires')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->findAll();

        return $this->render('proprietaires/index.html.twig', [
            'proprietaires' => $proprietaire
        ]);
    }

    #[Route('/proprietaires/{id}', name: 'app_prorprietaires_chatons')]
    public function afficher_chatons(ManagerRegistry $doctrine, $id): Response
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);
        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        return $this->render('proprietaires/afficher_chaton.html.twig', [
            'proprietaire' => $proprietaire,
            "chatons" => $proprietaire->getIdChaton(),
        ]);
    }

    #[Route('/proprietaires/ajouter', name: 'app_proprietaires_ajouter')]
    public  function  ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        $proprietaire = new Proprietaire();
        $form = $this->createForm(ProprietaireType::class, $proprietaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($proprietaire);

            $em->flush();

            return  $this->redirectToRoute("app_proprietaires");
        }

        return $this->render('proprietaires/ajouter.html.twig', [
            "formulaire"=>$form->CreateView()
        ]);
    }

    #[Route('/proprietaires/modifier/{id}', name: 'app_proprietaires_modifier')]
    public  function  modifier(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $proprietaires = $doctrine->getRepository(Proprietaire::class)->find($id);

        if (!$proprietaires){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        $form = $this->createForm(ProprietaireType::class, $proprietaires);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($proprietaires);

            $em->flush();

            return  $this->redirectToRoute("app_proprietaires");
        }

        return $this->render('proprietaires/modifier.html.twig', [
            "id"=>$id,
            "proprietaires"=>$proprietaires,
            "formulaire"=>$form->CreateView()
        ]);
    }

    #[Route('/proprietaires/supprimer/{id}', name: 'app_proprietaires_supprimer')]
    public  function  supprimer(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $proprietaires = $doctrine->getRepository(Proprietaire::class)->find($id);

        if (!$proprietaires){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        $form = $this->createForm(ProprietaireSupprimerType::class, $proprietaires);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->remove($proprietaires);

            $em->flush();

            return  $this->redirectToRoute("app_proprietaires");
        }

        return $this->render('proprietaires/supprimer.html.twig', [
            "id"=>$id,
            "proprietaires"=>$proprietaires,
            "formulaire"=>$form->CreateView()
        ]);
    }
}
