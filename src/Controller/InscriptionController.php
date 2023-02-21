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
use App\Repository\ProvinciaRepository;
use App\Entity\Provincia;
use Symfony\Bundle\SecurityBundle\Security;

class InscriptionController extends AbstractController
{
    #[Route('/', name: 'inscription_home')]
    public function index(Security $security): Response
    {
        if ($security->getUser() != null) {
            return $this->redirect('/admin');
        }
        return $this->render('/frontend/index.html.twig');
    }

    #[Route('/hazte-cliente', name: 'inscription_form')]
    public function inscription(Request $request, ManagerRegistry $doctrine, InscriptionRepository $inscRepository): Response
    {
        
        $host = explode(".",$request->getHost());
        if(count($host) == 1)
            $subdomain = '';
        else
            $subdomain = $host[0];
       
        
      
        $entityManager = $doctrine->getManager();
        
        //Obtenemos las provincias
        $provincias = $doctrine->getRepository(Provincia::class)->findAllSorted();

        //Si estamos enviando form, lo guardamos en BBDD
        if($request->getMethod() == 'POST'){
          
            $parameters = $request->request->all();
            
            $inscription = new Inscription();
            $inscription->setCIF($parameters['cif']);
            $inscription->setNombre($parameters['nombre']);
            $inscription->setEmail($parameters['email']);
            $inscription->setTelefono($parameters['telefono']);
            $inscription->setProvincia($parameters['provincia']);
            $inscription->setCondicioneslegales(true);
            $inscription->setCreated(true);
            $inscription->setUpdated(true);
            $inscription->setEstado(false);
            $inscription->setSubdominio($subdomain);


            $entityManager->persist($inscription);
            $entityManager->flush();
            $message = 'OK';
        }else{
            $message = null;
        }        
        


        return $this->render('/frontend/inscription.html.twig', [
           'provincias' => $provincias,
           'message' => $message
        ]);
    }

    #[Route('/hazte-cliente/OK', name: 'valid_inscription')]
    public function success() {
        return $this->render('valid_inscription.html.twig');
    }

    
}
