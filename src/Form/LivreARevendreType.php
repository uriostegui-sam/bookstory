<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Utilisateur;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LivreARevendreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du livre :',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du livre :',
            ])
            ->add('image', TextType::class, [
                'label' => 'Image (url) :',
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix :',
            ])
            ->add('categorie', EntityType::class, [
                'label' => 'Catégorie :',
                'class' => Categorie::class,
                'choice_label' => 'titre',
            ])
            ->add('auteur', EntityType::class, [
                'label' => 'Auteur :',
                'class' => Auteur::class,
                'choice_label' => 'nom',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Revendre ce livre',
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
            $event->getForm()->getData()->setRevendeur($options['utilisateur']);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('utilisateur');
        $resolver->setAllowedTypes('utilisateur', Utilisateur::class);

        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
