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

    #[Route('/privacy', name: 'app_about')]
    public function privacy(): Response
    {
        return $this->render('information/privacy.html.twig', [
            'controller_name' => 'InformationController',
        ]);
    }

}
