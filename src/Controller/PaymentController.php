<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
    #[Route('/checkout', name: 'checkout')]
    public function index1(Request $request, $stripe_sk): Response
    {
        Stripe::setApiKey($stripe_sk);

        $productName = $request->query->get('name');
        $productPrice = $request->query->get('price');

        // Convert the product price to cents
        $productPriceInCents = (int) ($productPrice * 100);

        $session = Session::create([
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
            'success_url'=> $this->generateUrl('ok',[],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'=>  $this->generateUrl('notokmeowmeow',[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/success-url', name: 'ok')]
    public function inde1x(): Response
    {
        return $this->render('payment/success.html.twig', []);
    }

    #[Route('/cancel-url', name: 'notokmeowmeow')]
    public function ind2ex(): Response
    {
        return $this->render('payment/notsuccess.html.twig', []);
    }
}
