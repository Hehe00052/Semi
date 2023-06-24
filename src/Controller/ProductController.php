<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Product; 
use App\Entity\Category; 
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;
use Symfony\Component\Form\FormTypeInterface;

class ProductController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {

    }

    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/Customer/products.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/admin/product', name: 'app_admin_add')]
    public function add(Request $req, EntityManagerInterface $connect, FileUploader $uploader): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            $file = $form->get("Photo")->getData();
            $fileName = $uploader->upload($file);
            $data->setPhoto($fileName);

            $connect->persist($data);
            $connect->flush();
            return new RedirectResponse($this->urlGenerator->generate('app_product_list'));
        }

        return $this->render('product/Admin/products.html.twig', [
            'product_form' => $form->createView(),
        ]);
    }
}
