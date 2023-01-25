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
    public function index(Request $request, InscriptionRepository $inscRepository): Response
    {
        $email = $request->request->get('search_email');
        
        if ($request->getMethod() == 'POST' && $email != null)
            $inscriptions = $inscRepository->findByEmail($email);
        else
            $inscriptions = $inscRepository->findAllSortedByDate();
        
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
            $inscription->setUpdated();
            $entityManager->flush();
            return $this->redirectToRoute('index_admin');
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form,
            'inscription' => $inscription
        ]);
    }

    #[Route('/admin/exportar', name: 'export')]
    public function exportUsers(InscriptionRepository $inscRepository) {
        $data = [['CIF', 'Nombre', 'Email', 'Telefono', 'Provincia', 'Fecha creacion', 'Fecha modificacion', 'Estado']];

        $users = $inscRepository->findAllSortedByDate();

        foreach ($users as $user) {
            $data[] = array($user->getCIF(), $user->getNombre(), $user->getEmail(), $user->getTelefono(), $user->getProvincia(), $user->getCreated(), $user->getUpdated(), $user->getEstado());
        }

        // Create a response object
        $response = new Response(
            implode("\n", array_map(function ($data) {
                return implode(',', $data);
            }, $data))
        );

        // Set the response headers
        $response->headers->set('Content-type', 'text/csv');
        $response->headers->set('Content-Disposition', 'filename="usuarios.csv";');

        // Send the response
        return $response;
    }
}
