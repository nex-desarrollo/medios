<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\InscriptionRepository;
use App\Form\InscriptionType;
use App\Entity\Inscription;
use Doctrine\Persistence\ManagerRegistry;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'index_admin')]
    public function index(InscriptionRepository $inscRepository): Response
    {
        $inscriptions = $inscRepository->findAll();
        return $this->render('admin/users.html.twig', [
            'inscriptions' => $inscriptions
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'edit_user', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inscription $inscription, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('index_admin');
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form,
            'inscription' => $inscription
        ]);
    }
}