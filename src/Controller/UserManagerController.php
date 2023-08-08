<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserManagerController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $ug)
    {
    }
    #[Route('/user/manager', name: 'app_user_manager')]
    public function index(EntityManagerInterface $em): Response
    {
        $query = $em->createQuery('SELECT u FROM App\Entity\User u');
        $lUser = $query->getResult();
        return $this->render('user_manager/index.html.twig', [
            "data" => $lUser 
        ]);
    }

    #[Route('/user/manager/add', name: 'app_add_user_manager')]
    public function add(EntityManagerInterface $em, Request $req, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $u = new User();
        $form = $this->createForm(UserFormType::class, $u);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()) {
            $u = $form->getData();
            $u->setPassword(
                $userPasswordHasher->hashPassword(
                    $u,
                    $form->get('password')->getData()
                )
            );
            $u->setRoles(["ROLE_ADMIN"]);
            $em->persist($u);
            $em->flush();

            return new RedirectResponse($this->ug->generate('app_user_manager')); #kết nối dữ liều giữ trang sanpham và trang sanphamds
            // return new Response("<p>Product Add Succefully!</p>");
        }

        return $this->render('user_manager/form.html.twig', [
            'u_form' => $form->createView(),
        ]);
    }
<<<<<<< HEAD

    #[Route('/user{id}/delete', name: 'app_delete_user')]
    public function delete(Request $req, int $id, EntityManagerInterface $connect): Response
    {
        $user = $connect->find(User::class,$id);
        $connect->remove($user);
        $connect->flush();
        return new RedirectResponse($this->ug->generate('app_user_manager'));
    }
=======
>>>>>>> Di
}
