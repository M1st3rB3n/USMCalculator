<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Competition;
use App\Entity\ElementArtistique;
use App\Entity\ElementTechnique;
use App\Entity\Epreuve;
use App\Entity\Niveau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpreuveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'épreuve',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('competition', EntityType::class, [
                'class' => Competition::class,
                'choice_label' => 'nom',
                'label' => 'Compétition',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Choisir une compétition',
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Choisir une catégorie',
            ])
            ->add('niveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' => 'nom',
                'label' => 'Niveau',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Choisir un niveau',
            ])
            ->add('elementsTechniques', EntityType::class, [
                'class' => ElementTechnique::class,
                'choice_label' => 'nom',
                'label' => 'Éléments Techniques',
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'mb-3'],
                'label_attr' => ['class' => 'fw-bold'],
                'group_by' => function ($choice, $key, $value) {
                    return 'Later';
                },
            ])
            ->add('elementsArtistiques', EntityType::class, [
                'class' => ElementArtistique::class,
                'choice_label' => 'nom',
                'label' => 'Éléments Artistiques',
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'mb-3'],
                'label_attr' => ['class' => 'fw-bold'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Epreuve::class,
        ]);
    }
}
