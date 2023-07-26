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
    public function userlist(Request $req, EntityManagerInterface $quer): Response
    {
        $quer = $quer->createQuery('SELECT product FROM App\Entity\Product product');
        $listproduct = $quer->getResult();

        return $this->render('product/Customer/products.html.twig', [
            'data' => $listproduct
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

            $file = $form->get("photo")->getData();
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

    #[Route('admin/product/list', name: 'app_product_list')]
    public function list(Request $req, EntityManagerInterface $quer): Response
    {
        $quer = $quer->createQuery('SELECT product FROM App\Entity\Product product');
        $listproduct = $quer->getResult();

        return $this->render('product/admin/list.html.twig', [
            'data' => $listproduct
        ]);
    }

    #[Route('/product{id}', name: 'app_edit_product')]
    public function edit(Request $req, int $id, EntityManagerInterface $connect, FileUploader $uploader): Response
    {
        $product = $connect->find(Product::class,$id);
        $form = $this ->createForm(ProductType::class,$product);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            $file = $form->get("photo")->getData();
            if ($file)
            {
                $fileName = $uploader->upload($file);
                $data->setPhoto($fileName);
            } 

            $product->setName($data->getName())->setPrice($data->getPrice())->setDescription($data->getDescription());
            $connect->flush();
            return new RedirectResponse($this->urlGenerator->generate('app_product_list'));
        }

        return $this->render('product/admin/products.html.twig', [
            'product_form' => $form->createView(),
        ]);
    }

    #[Route('/product{id}/delete', name: 'app_delete_product')]
    public function delete(Request $req, int $id, EntityManagerInterface $connect): Response
    {
        $product = $connect->find(Product::class,$id);
        $connect->remove($product);
        $connect->flush();
        return new RedirectResponse($this->urlGenerator->generate('app_product_list'));
    }

    #[Route('/detailproduct{id}', name: 'app_product_detail')]
    public function detail(Request $req, int $id,EntityManagerInterface $quer): Response
    {
        $quer = $quer->createQuery("SELECT product FROM App\Entity\Product product WHERE product.id = " . $id);
        $listproduct = $quer->getResult();

        return $this->render('product/Customer/detail.html.twig', [
            'data' => $listproduct
        ]);
    }

    #[Route('admin/detailproduct{id}', name: 'app_product_detail_ad')]
    public function detailad(Request $req, int $id,EntityManagerInterface $quer): Response
    {
        $quer = $quer->createQuery("SELECT product FROM App\Entity\Product product WHERE product.id = " . $id);
        $listproduct = $quer->getResult();

        return $this->render('product/Admin/details.html.twig', [
            'data' => $listproduct
        ]);
    }
}
