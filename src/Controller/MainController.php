<?php
//Controller pour la page Accueil
namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ContactRepository $contactRepository): Response
    {
        //afficher le contact de kcs transport
        $contact = $contactRepository->findOneBy(
            ["name" => "Kcs Transport"]
        );

        
        return $this->render('main/index.html.twig', [
            'contact' => $contact,
        ]);
    }
}
