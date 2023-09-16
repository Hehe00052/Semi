<?php

namespace App\Controller;

use App\Cart\CartManager;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Form\OrderType;
use App\Form\OrderItemType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    #[Route('/order', name: 'app_order')]
    public function index(EntityManagerInterface $em, Request $req): Response
    {
        $order = new Order();
        $message = $req->query->get('message');
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($req);
        $session = $req->getSession();
        $cart_manager = $session->get('cart', new CartManager());
        $time = new \DateTime();
        $time->format('YYYY-mm-dd');

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $em->getConnection()->beginTransaction();
            try {
                $order->setTotalPrice($cart_manager->getAmount());
                $order->setDateOut($time);
                $order->setStatus(false);
                $em->persist($order);
                $cart_items = $cart_manager->getItems();
                foreach ($cart_items as $key => $cart_item) {
                    $orderItem = new OrderItem();
                    $product = $em->find(Product::class,$cart_item->getProduct()->getId());
                    $orderItem->setItem($product);
                    $orderItem->setQuantity($cart_item->quantity);
                    $orderItem->setPrice($cart_item->getProduct()->getPrice());
                    $orderItem->setOrderId($order);
                    $em->persist($orderItem);
                    $em->flush();
                }
                $em->flush();
                $em->getConnection()->commit();
                $session->set('cart', new CartManager());
            } catch (Exception $e) {
                $em->getConnection()->rollBack();
                return new RedirectResponse($this->urlGenerator->generate('app_order',["message"=>"Error! Can't create the order"]));
            }
            return new RedirectResponse($this->urlGenerator->generate('app_product',["message"=>"Your order has been confirmed"]));
        }

        return $this->render('order/index.html.twig', [
            'order_form' => $form->createView(),
            'cart_manager' => $cart_manager,
            'message' => $message
        ]);
    }

    #[Route('admin/order/list', name: 'app_order_list')]
    public function list(Request $req, EntityManagerInterface $quer): Response
    {
        $quer = $quer->createQuery('SELECT ord FROM App\Entity\Order ord');
        $listorder = $quer->getResult();

        return $this->render('order/orderlist.html.twig', [
            'data' => $listorder
        ]);
    }
    
    #[Route('admin/itemlist', name: 'itemlist')]
    public function item(Request $req, EntityManagerInterface $quer): Response
    {
        $quer = $quer->createQuery('SELECT item FROM App\Entity\OrderItem item');
        $listitem = $quer->getResult();
        
        return $this->render('order/orderitem.html.twig', [
            'data' => $listitem
        ]);
    }

    #[Route('admin/order/{id}', name: 'app_orderitem_view')]
    public function vieworderitem(Request $req, int $id,  EntityManagerInterface $connect): Response
    {
        $ord = $connect->find(Order::class,$id);
        $listproduct = $ord->getOrderItems();
        return $this->render('order/orderitem.html.twig', [
            'data' => $listproduct
        ]);
    }

    #[Route('change{id}', name: 'app_mark')]
    public function change(Request $req, int $id,  EntityManagerInterface $connect): Response
    {
        $product = $connect->find(Order::class,$id);
        $product->setStatus(1);
        $connect->flush();
        return new RedirectResponse($this->urlGenerator->generate('app_order_list'));
    }
}
