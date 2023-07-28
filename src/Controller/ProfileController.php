<?php

// src/Controller/ProfileController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;



class ProfileController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile/edit', name: 'app_edit_profile')]
    public function editProfile(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle password update
            if ($form->get('plainPassword')->getData()) {
                $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($hashedPassword);
            }

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

    #[Route('/profile/delete', name: 'app_delete_account')]
public function deleteAccount(
    Request $request,
    TokenStorageInterface $tokenStorage,
    EventDispatcherInterface $eventDispatcher,
    SessionInterface $session,
    UrlGeneratorInterface $urlGenerator
): Response {
    // Ensure the user is logged in
    $user = $this->getUser();
    if (!$user instanceof UserInterface) {
        throw new AccessDeniedException('You must be logged in to delete your account.');
    }

    // Get the current Token
    $token = $tokenStorage->getToken();

    // Dispatch the LogoutEvent to handle user logout with the correct Token
    $logoutEvent = new LogoutEvent($request, $token);
    $eventDispatcher->dispatch($logoutEvent);

    // Clear the token storage
    $tokenStorage->setToken(null);

    // Invalidate the session to ensure the user is properly logged out
    $session->invalidate();

    // Handle account deletion
    $this->entityManager->remove($user);
    $this->entityManager->flush();

    // Redirect to the homepage
    return new RedirectResponse($urlGenerator->generate('app_home'));
}
}