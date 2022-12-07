<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Form\ChatonSupprimerType;
use App\Form\ChatonType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ChatonsController extends AbstractController
{
    #[Route('/chatons/{id}', name: 'app_chatons_voir')]
    public function index($id, ManagerRegistry $doctrine): Response
    {
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        return $this->render('chatons/index.html.twig', [
            'categorie' => $categorie,
            "chatons" => $categorie->getChatons(),
        ]);
    }

    #[Route('/chaton/ajouter', name: 'app_chatons_ajouter')]
    public  function  ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        $chaton = new Chaton();
        $form = $this->createForm(ChatonType::class, $chaton);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($chaton);

            $em->flush();

            return  $this->redirectToRoute("app_categories");
        }

        return $this->render('chatons/ajouter.html.twig', [
            "formulaire"=>$form->CreateView()
        ]);
    }

    #[Route('/chaton/modifier/{id}', name: 'app_chatons_modifier')]
    public  function  modifier(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $chatons = $doctrine->getRepository(Chaton::class)->find($id);

        if (!$chatons){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        $form = $this->createForm(ChatonType::class, $chatons);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($chatons);

            $em->flush();

            return  $this->redirectToRoute("app_categories");
        }

        return $this->render('chatons/modifier.html.twig', [
            "id"=>$id,
            "chaton"=>$chatons,
            "formulaire"=>$form->CreateView()
        ]);
    }

    #[Route('/chaton/supprimer/{id}', name: 'app_chatons_supprimer')]
    public  function  supprimer(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $chatons = $doctrine->getRepository(Chaton::class)->find($id);

        if (!$chatons){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        $form = $this->createForm(ChatonSupprimerType::class, $chatons);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->remove($chatons);

            $em->flush();

            return  $this->redirectToRoute("app_categories");
        }

        return $this->render('chatons/supprimer.html.twig', [
            "id"=>$id,
            "chaton"=>$chatons,
            "formulaire"=>$form->CreateView()
        ]);
    }

}
