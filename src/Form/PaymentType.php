<?php

namespace App\Form;

use App\DTO\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulaire', TextType::class, [
                'label' => 'Titulaire de la carte',
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail'
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays',
            ])
            ->add('numCarte', IntegerType::class, [
                'label' => 'Numéro de la carte'
            ])
            ->add('expiration', DateType::class, [
                'label' => "Date d'expiration",
            ])
            ->add('cryptogramme', IntegerType::class, [
                'label' => 'Criptogramme'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Payer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
