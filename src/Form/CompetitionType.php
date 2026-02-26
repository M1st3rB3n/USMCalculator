<?php

namespace App\Form;

use App\Entity\Competition;
use App\Entity\Epreuve;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la compétition',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('epreuves', EntityType::class, [
                'class' => Epreuve::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'label' => 'Sélectionner les épreuves',
                'attr' => ['class' => 'mb-3'],
                'choice_attr' => function () {
                    return ['class' => 'form-check-label mb-1'];
                },
                'label_attr' => ['class' => 'fw-bold'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Competition::class,
        ]);
    }
}
