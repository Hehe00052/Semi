<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Product; 
use App\Entity\Category; 
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;
use Symfony\Component\Form\FormTypeInterface;

class CategoryController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {

    }

    #[Route('/admin/category', name: 'app_admin_add_cate')]
    public function addcate(Request $req, EntityManagerInterface $connect, FileUploader $uploader): Response
    {
        $product = new Category();
        $form = $this->createForm(CategoryType::class, $product);
        $form->handleRequest($req);
 
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            $file = $form->get("Photo")->getData();
            $fileName = $uploader->upload($file);
            $data->setPhoto($fileName);
            $connect->persist($data);
            $connect->flush();
            return new RedirectResponse($this->urlGenerator->generate('app_product_list_cate'));
        }

        return $this->render('category/index.html.twig', [
            'product_form' => $form->createView(),
        ]);
    }

    #[Route('admin/listcate', name: 'app_product_list_cate')]
    public function listcate(Request $req, EntityManagerInterface $quer): Response
    {
        $quer = $quer->createQuery('SELECT category FROM App\Entity\Category category');
        $listproduct = $quer->getResult();

        return $this->render('category/list.html.twig', [
            'data' => $listproduct
        ]);
    }

    #[Route('/cate{id}', name: 'app_edit_cate')]
    public function editcate(Request $req, int $id, EntityManagerInterface $connect, FileUploader $uploader): Response
    {
        $cate = $connect->find(Category::class,$id);
        $form = $this ->createForm(CategoryType::class,$cate);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            $file = $form->get("Photo")->getData();
            if ($file)
            {
                $fileName = $uploader->upload($file);
                $data->setPhoto($fileName);
            } 

            $cate->setName($data->getName());
            $connect->flush();
            return new RedirectResponse($this->urlGenerator->generate('app_product_list_cate'));
        }

        return $this->render('category/index.html.twig', [
            'product_form' => $form->createView(),
        ]);
    }

    #[Route('/cate{id}/delete', name: 'app_delete_cate')]
    public function delete(Request $req, int $id, EntityManagerInterface $connect): Response
    {
        $cate = $connect->find(Category::class,$id);
        $connect->remove($cate);
        $connect->flush();
        return new RedirectResponse($this->urlGenerator->generate('app_product_list_cate'));
    }

}
