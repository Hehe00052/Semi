<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Staffs;
use App\Entity\Catestaff;
use App\Form\StaffType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StaffController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {

    }

    #[Route('/add/staff', name: 'app_add_staff')]
    public function index(EntityManagerInterface $em, Request $req, FileUploader $fileUploader): Response
    {

        $staff = new Staffs();
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $file = $form->get("Photo")->getData();
            $fileName = $fileUploader->upload($file);
            $data->setPhoto($fileName);

            $em->persist($data);
            $em->flush();

            return new RedirectResponse($this->urlGenerator->generate('app_staff')); #kết nối dữ liều giữ trang sanpham và trang sanphamds
            // return new Response("<p>Product Add Succefully!</p>");
        }

        return $this->render('staff/index.html.twig', [
            'staff_form' => $form->createView(),
        ]);
    }

    #[Route('/staff', name: 'app_staff')]
    public function list_staff(EntityManagerInterface $em): Response
    {

        $query = $em->createQuery('SELECT staff FROM App\Entity\Staffs staff');
        $lSp = $query->getResult();
        return $this->render('staff/list.html.twig', [
            "data"=> $lSp
        ]);
    }

    #[Route('edit/staff/{id}', name: 'app_edit_staff')]
    public function edit(EntityManagerInterface $em, int $id, Request $req, FileUploader $fileUploader): Response
    {
        $staff = $em->find(Staffs::class, $id); 
        // tìm kiếm khóa chính id
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $file = $form->get("Photo")->getData();
            if($file) 
            {
                $fileName = $fileUploader->upload($file);
                $data->setPhoto($fileName);
            }
            $staff->setName($data->getName())->setPhonenumber($data->getPhonenumber());
            $em->flush();
            
            return new RedirectResponse($this->urlGenerator->generate('app_staff'));
        
    }
    return $this->render('staff/index.html.twig', [
        'staff_form' => $form->createView(),
        ]);
    }

    #[Route('/staff/{id}delete/', name: 'app_delete_staff')]
    public function delete(EntityManagerInterface $em, int $id, Request $req): Response
    {
        $staff = $em ->find(Staffs::class, $id);
        $em->remove($staff);
        $em->flush();   
            // $em->flush(); thực hiện trên cơ sở dữ liệu 
        return new RedirectResponse($this->urlGenerator->generate('app_staff'));
    }

    #[Route('catestaff/{id}', name: 'app_staff_catestaff')]
    public function listSPinCate (EntityManagerInterface $em, int $id, Request $req): Response
    {
        $cate = $em ->find(Catestaff::class, $id);
        $lSp = $cate->getStaffs();
        return $this->render('staff/list.html.twig', [
            "data"=>$lSp   
        ]);
    }
}
