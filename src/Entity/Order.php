<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Customer_name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Name must have at least {{ limit }} letters.',
                    ]),
                ],
            ])
            ->add('Customer_address',TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Please enter your REAL and FULL address <3',
                    ]),
                ],
            ])
            ->add('Customer_phone', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{10}$/', // Adjust the pattern as needed
                        'message' => 'Please enter a valid 10-digit phone number.',
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 10,
                        'exactMessage' => 'Phone number must be exactly 10 digits.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
