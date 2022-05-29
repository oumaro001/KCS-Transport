<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ListContactController extends AbstractController
{
    #[Route('/liste/contact', name: 'list_contact',methods: ['GET', 'POST'])]
    public function index(ContactRepository $contactRepository): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }

        $contact = $contactRepository->findAll();

        return $this->render('list_contact/list_contact.html.twig', [
            'contact' => $contact,
        ]);
    }
}
