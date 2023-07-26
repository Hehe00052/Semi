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
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {

    }

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
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/needhelp', name: 'app_admin_request')]
    public function list(Request $req, EntityManagerInterface $quer): Response
    {
        $quer = $quer->createQuery('SELECT contact FROM App\Entity\Contact contact');
        $listproduct = $quer->getResult();

        return $this->render('contact/admin/list.html.twig', [
            'data' => $listproduct
        ]);
    }
    #[Route('/admin/contact/delete/{id}', name: 'app_admin_delete')]
    public function delete(Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($contact);
        $entityManager->flush();

        return new RedirectResponse($this->urlGenerator->generate('app_admin_request'));
    }

}
