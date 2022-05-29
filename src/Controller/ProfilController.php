<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\UpdatePasswordType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;




#[Route('/profil')]
class ProfilController extends AbstractController
{



    #[Route('/{id}', name: 'app_profil')]
    public function profil(User $user, CarRepository $carRepository): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }


        $car = $carRepository->findAll();

        return $this->render('profil/profil.html.twig', [
            'user' => $user,
            'car' => $car,
        ]);
    }


    #[Route('/{id}/modifier_mot-de-passe/', name: '_edit_password', methods: ['GET', 'POST'])]
    public function editEmail(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher,)
    {


        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UpdatePasswordType::class);
        $form->handleRequest($request);

        $password = $user->getPassword();

        //dump($password);

        if ($form->isSubmitted() && $form->isValid()) {

            $pass = $form->get('password')->getData();

            if (password_verify($pass, $user->getPassword())) { //compare les 2 mot de passe
                $passwordEdit =  $userPasswordHasher->hashPassword( //hash le mot de passe
                    $user,
                    $form->get('new_password')->getData(),
                );
                $user->setPassword($passwordEdit);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe modifié avec succès');

                return $this->redirectToRoute('app_profil', [
                    'id' => $user->getId()
                ], Response::HTTP_SEE_OTHER);
                
            } else {
                $this->addFlash('danger', 'Mot de passe invalide');
            }
        }

        return $this->renderForm('profil/password_update.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
