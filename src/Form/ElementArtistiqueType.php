<?php

namespace App\Form;

use App\Entity\ElementArtistique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElementArtistiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'élément',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('score', NumberType::class, [
                'label' => 'Score de base',
                'attr' => ['class' => 'form-control', 'step' => '0.01'],
                'required' => false,
            ])
            ->add('QoE', NumberType::class, [
                'label' => 'QoE',
                'attr' => ['class' => 'form-control', 'step' => '0.01'],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ElementArtistique::class,
        ]);
    }
}
