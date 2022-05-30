<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserByCarController extends AbstractController
{
    #[Route('/{id}/user/by/car', name: 'app_user_by_car', methods:['GET'])]
    public function index(User $user): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user_by_car/userBycar.html.twig', [
            'user' => $user,
        ]);
    }
}
