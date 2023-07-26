<?php

namespace App\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
class ProfileController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/profile/edit', name: 'app_edit_profile')]
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_profile');
        }
        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/profile', name: 'app_profile')]
    public function showProfile(): Response
    {
        $user = $this->getUser(); 

        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }
}
