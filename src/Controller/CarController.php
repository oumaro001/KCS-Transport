<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\User;
use App\Form\CarType;
use App\Form\FilteredByRegisterType;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/vehicule')]
class CarController extends AbstractController
{

 

    #[Route('/', name: 'app_car_index', methods: ['GET','POST'])]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {   
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        //formulaire pour la barre de recherche selon l 'immatriculation (register)
        $form = $this->createForm(FilteredByRegisterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $register = $form->get('register')->getData();

            if(!empty($register)) {
                $cars = $entityManager
                ->getRepository(Car::class)
                ->findOneBy([
                    'register' => $register,
                
                ]);

                if(!isset($cars)){

                    $this->addFlash('danger', 'Nom incorrect');

                }else
              
                return $this->render('car/show.html.twig', [
                    'car' => $cars,
                ]);
  
        }else $this->addFlash('danger', 'le champ n\'est pas remplie');
    
        }

        $cars = $entityManager
            ->getRepository(Car::class)
            ->findAll();


        return $this->render('car/index.html.twig', [
            'cars' => $cars,
            'form' =>$form->createView(),

            
        ]);
    }

    #[Route('/nouveau_car', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($car);
            $entityManager->flush();

            $this->addFlash('success', 'Véhicule ajoutée avec succès.');


            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_show', methods: ['GET','POST'])]
    public function show(Car $car,UserRepository $userRepository,): Response
    {   
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        
        $users = $userRepository->findAll();


        return $this->render('car/show.html.twig', [
            'car' => $car,
            'users' => $users,
        ]);
    }

    #[Route('/{id}/editer_car', name: 'app_car_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Car $car, EntityManagerInterface $entityManager ): Response
    {   
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Véhicule modifié avec succés.');


            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
            
        ]);
    }

    #[Route('/{id}', name: 'app_car_delete', methods: ['POST'])]
    public function delete(Request $request, Car $car, EntityManagerInterface $entityManager,UserRepository $userRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

       
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->request->get('_token'))) {

            $carImages = $car->getImageName();

            if($carImages){
                    $nomImage= $this->getParameter("app.path.users_images").'/'. $carImages ;
    
                    if(file_exists($nomImage)){
                        unlink($nomImage);
                    }
    
                
            }

            $entityManager->remove($car);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Véhicule supprimé avec succés.');


        return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
    }
}
