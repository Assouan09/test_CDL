<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use App\Entity\Auteur;
use App\Entity\Livre;
use App\Form\LivreType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string', TextType::class, [
                'label' => 'Recherche',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre recherche ...',
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' =>  'Categorie',
                'required' => false,
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true
            ])

            ->add('auteur', EntityType::class, [
                'label' =>  'Auteur',
                'required' => false,
                'class' => Auteur::class,
                'multiple' => true,
                'expanded' => true
            ])

            ->add('nom', EntityType::class, [
                'label' =>  'Titre du livre',
                'required' => false,
                'class' => Livre::class,
                'multiple' => true,
                'expanded' => true
            ])

            /*
            ->add('date', LivreType::class, [
                'label' =>  'Date de publication'
            ])
            */
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crsf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}