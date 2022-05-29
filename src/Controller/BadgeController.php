<?php

namespace App\Controller;

use Carbon\carbon;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BadgeController extends AbstractController
{
    
    #[Route('/badge/{id}', name: 'app_badge')]
    public function index(Request $request, User $user, EntityManagerInterface $entityManager): Response
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
