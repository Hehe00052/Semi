<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $req, EntityManagerInterface $connect): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $connect->persist($data);
            $connect->flush();
            return new RedirectResponse($this->urlGenerator->generate('app_contact'));
        }
        return $this->render('contact/Customer/contact.html.twig', [
            'product_form' => $form->createView(),
        ]);
    }
}
