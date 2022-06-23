<?php

//Controller pour les users
namespace App\Controller;

use App\Entity\Car;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\CarRepository;
use App\Form\RegistrationFormType;
use App\Form\FilteredByRegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



#[IsGranted('ROLE_ADMIN')]
#[Route('/salaries')]
class UserController extends AbstractController
{   
    
   

    //afficher tous les users
    #[Route('/', name: 'app_user_index', methods: ['GET', 'POST'])]
    public function index(EntityManagerInterface $entityManager,Request $request,): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        //Barre de recherche de user selon son véhicule
        $form = $this->createForm(FilteredByRegisterType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            if($form->get('car')->getData() == null){
                $this->addFlash('danger','Veuillez sélectionner un véhicule');

            }else{ 
             
            $register = $form->get('car')->getData();

               if(!empty($register)){

                    $user = $entityManager
                    ->getRepository(User::class)
                    ->findOneBy([
                        'car' => $register->getId(), ]);
  

                }

            

            if(!isset($user)){

                $this->addFlash('danger','Aucun salarié ne possède se voiture.');
            }else{


                $cars = $entityManager
                ->getRepository(Car::class)
                ->findAll();

                
              
                return $this->render('user/show.html.twig', [
                    'user' => $user,
                    'cars' => $cars,
                ]);
  
        }
        
    }
        }


        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();

        

        $car = $entityManager
            ->getRepository(Car::class)
            ->findAll();

        return $this->render('user/salaries.html.twig', [
            'users' => $users,
            'car' => $car,
            'form' =>$form->createView(),
        ]);
    
}

    //ajouter un nouveau user
    #[Route('/nouveau_salarie', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher): Response
    {  
         if (!$this->isGranted('ROLE_ADMIN')) {
        return $this->redirectToRoute('app_login');
         }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData() ));
            
         
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau salarié ajouté avec succès');

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    //afficher un user
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user , CarRepository $carRepository): Response
    {   
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        $cars = $carRepository->findAll();

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'cars' => $cars,
        ]);
    }

    //modifier un user
    #[Route('/{id}/editer_salaries', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher): Response
    {   
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'Salarié modifié avec succès');
    
            $entityManager->flush();



            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    //supprimer un user
    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {   
        if (!$this->isGranted('ROLE_ADMIN')) {
        return $this->redirectToRoute('app_login');
         }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {


            
            $images = $user->getImageName();

        if($images){
                $nomImage= $this->getParameter("app.path.users_images").'/'. $images ; //chemin vers image
                //supprime image dans les dossier users
                if(file_exists($nomImage)){
                    unlink($nomImage);
                }

            
        }
            $entityManager->remove($user);
            $entityManager->flush();
        }
        
        $this->addFlash('success', 'Salarié supprimé avec succès');

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
