<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Niveau;
use App\Entity\Patineuse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatineuseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('anneeDeNaissance', IntegerType::class, [
                'label' => 'Année de naissance',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('niveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' => 'nom',
                'label' => 'Niveau',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Choisir un niveau',
            ])
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'nom',
                'label' => 'Club',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Choisir un club',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patineuse::class,
        ]);
    }
}
