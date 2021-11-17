<?php

namespace App\Form;

use App\DTO\SearchLivreCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchLivreCriteriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Par titre :',
                'required' => false,
            ])
            ->add('auteur', TextType::class, [
                'label' => 'Par auteur :',
                'required' => false,
            ])
            ->add('revendeur', TextType::class, [
                'label' => 'Par revendeur :',
                'required' => false,
            ])
            ->add('categorie', TextType::class, [
                'label' => 'Par categorie :',
                'required' => false,
            ])
            ->add('minPrix', MoneyType::class, [
                'label' => 'Par prix minimun',
                'required' => false,
            ])
            ->add('maxPrix', MoneyType::class, [
                'label' => 'Par prix maximum',
                'required' => false,
            ])
            ->add('page', NumberType::class, [
                'label' => 'Page :',
            ])
            ->add('limit', NumberType::class, [
                'label' => 'Nombre de résultat maximum :'
            ])
            ->add('orderBy', ChoiceType::class, [
                'label' => 'Trier par :',
                'choices' => [
                    'Titre' => 'title',
                    'Auteur' => 'auteur',
                    'Revendeur' => 'revendeur',
                    'Prix Minimum' => 'minPrix',
                    'Prix Maximum' => 'manPrix',
                    'Date' => 'dateMiseAJour',
                ]
            ])
            ->add('direction', ChoiceType::class, [
                'label' => 'Sens du trie :',
                'choices' => [
                    'Croissant' => 'ASC',
                    'Decroissant' => 'DESC'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchLivreCriteria::class,
            'data' => new SearchLivreCriteria(),
            'method' => 'GET',
        ]);
    }
}
