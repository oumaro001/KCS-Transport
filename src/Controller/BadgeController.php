<?php
//Controller pour la page badge

namespace App\Controller;


use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BadgeController extends AbstractController
{
    
    #[Route('/badge/{id}', name: 'app_badge')]
    public function index( User $user, ): Response
    {   
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }


        $date = new DateTimeImmutable();
        

        return $this->render('badge/badge.html.twig', [
            'user' => $user,
            'date' => $date,

        ]);
    }
}
