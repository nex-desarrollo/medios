<?php

namespace App\Controller;

use App\Form\InscriptionType;
use App\Entity\Inscription;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\InscriptionRepository;
use Symfony\Bundle\SecurityBundle\Security;

class InscriptionController extends AbstractController
{
    #[Route('/', name: 'app_inscription')]
    public function index(Security $security): Response
    {
        if ($security->getUser() != null) {
            return $this->redirect('/admin');
        }
        return $this->render('index.html.twig');
    }

    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request, ManagerRegistry $doctrine, InscriptionRepository $inscRepository): Response
    {
        $inscription = new Inscription();
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($inscRepository->isUnique($inscription->getCIF())) {
                $inscription->setEstado(false);
                $inscription->setCreated();
                $inscription->setUpdated();
                
                $entityManager->persist($inscription);
                $entityManager->flush();
                return $this->redirectToRoute('valid_inscription');
            } else {
                print_r('El CIF introducido ya existe. Vuelve atrás.');
                die();
            } 
        }
        
        return $this->render('inscription.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/inscription/OK', name: 'valid_inscription')]
    public function success() {
        return $this->render('valid_inscription.html.twig');
    }

    #[Route('/legal', name: 'legal')]
    public function legal() {
        return $this->render('legal.html.twig');
    }
}
