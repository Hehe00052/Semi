<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InformationController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(): Response
    {
        return $this->render('information/about.html.twig', [
            'controller_name' => 'InformationController',
        ]);
    }

    #[Route('/privacy', name: 'app_privacy')]
    public function privacy(): Response
    {
        return $this->render('information/privacy.html.twig', [
            'controller_name' => 'InformationController',
        ]);
    }

    #[Route('/home', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('information/home.html.twig', [
            'controller_name' => 'InformationController',
        ]);
    }

    #[Route('/home/user', name: 'app_home_user')]
    public function homeuser(): Response
    {
        return $this->render('information/homeuser.html.twig', [
            'controller_name' => 'InformationController',
        ]);
    }

}
