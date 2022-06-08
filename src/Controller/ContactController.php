<?php
//Controller des contacts
namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/contact')]
class ContactController extends AbstractController
{
    //afficher tous les contacts
    #[Route('/', name: 'app_contact_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {  
         if (!$this->isGranted('ROLE_ADMIN')) {
        return $this->redirectToRoute('app_login');
    }

        $contacts = $entityManager
            ->getRepository(Contact::class)
            ->findAll();

        return $this->render('contact/contact.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    
        //ajouter nouveau contact
    #[Route('/nouveau_contact', name: 'app_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Contact ajouté avec succès.');


            return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

   
        //afficher un contact
    #[Route('/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {   
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    
    //modifier un contact
    #[Route('/{id}/editer_contact', name: 'app_contact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {  
         if (!$this->isGranted('ROLE_ADMIN')) {
        return $this->redirectToRoute('app_login');
    }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        
            $this->addFlash('success', 'Contact modifié avec succès.');
  

            return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

   
    //supprimer un contact
    #[Route('/{id}', name: 'app_contact_delete', methods: ['POST'])]
    public function delete(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {   
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Contact supprimé avec succès.');

        return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
    }

 
}
