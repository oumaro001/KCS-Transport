<?php
//Affiche le véhicule selon son chauffeur
namespace App\Controller;

use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarByUserController extends AbstractController
{
    #[Route('/{id}/car/by/user', name: 'app_car_by_user')]
    public function showcar(Car $car): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('car_by_user/carByUser.html.twig', [
            'car' => $car,
        ]);
    }
}
