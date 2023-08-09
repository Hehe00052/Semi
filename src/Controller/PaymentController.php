<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Cart\CartManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class PaymentController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
    #[Route('/checkout', name: 'checkout')]
    public function index1(Request $request, $stripe_sk, EntityManagerInterface $em): Response
    {
        $order = new Order();
        $message = $request->query->get('message');
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        $session = $request->getSession();
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
            Stripe::setApiKey($stripe_sk);
            $successUrl = $this->generateUrl('ok', ['orderId' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            $productName = "hello";
            $productPrice = $order->getTotalPrice();
            $productPriceInCents = (int) ($productPrice * 100);
            $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data'=> [
                    'currency'=> 'usd',
                    'product_data'=> [
                        'name'=> $productName,
                    ],
                    'unit_amount'=> $productPriceInCents,
                ],
                'quantity'=> 1,
            ]],
            'mode'=> 'payment',
            'success_url'=> $successUrl,
            'cancel_url'=>  $this->generateUrl('notokmeowmeow',[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return $this->redirect($session->url, 303);}
        return $this->render('order/index.html.twig', [
            'order_form' => $form->createView(),
            'cart_manager' => $cart_manager,
            'message' => $message
        ]); 
    }

    #[Route('/success-url', name: 'ok')]
    public function success(Request $request, EntityManagerInterface $em): Response
    {
        $orderId = $request->query->get('orderId');
        $order = $em->getRepository(Order::class)->find($orderId);
        if (!$order) {
        return $this->redirectToRoute('error');
    } else{
        $order->setStatus(1);
        $em->flush();
    }
        return $this->render('payment/success.html.twig', []);
    }

    #[Route('/cancel-url', name: 'notokmeowmeow')]
    public function ind2ex(): Response
    {
        return $this->render('payment/notsuccess.html.twig', []);
    }
}
