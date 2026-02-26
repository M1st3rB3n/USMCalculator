<?php

namespace App\Form;

use App\Entity\ElementArtistique;
use App\Entity\Epreuve;
use App\Entity\NoteArtistique;
use App\Entity\Patineuse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteArtistiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', NumberType::class, [
                'required' => false,
                'label' => 'Note',
                'attr' => ['class' => 'form-control'],
            ])
        ;

        if ($options['include_relations']) {
            $builder
                ->add('patineuse', EntityType::class, [
                    'class' => Patineuse::class,
                    'choice_label' => function (Patineuse $patineuse) {
                        return $patineuse->getPrenom().' '.$patineuse->getNom();
                    },
                    'label' => 'Patineuse',
                    'attr' => ['class' => 'form-select'],
                ])
                ->add('epreuve', EntityType::class, [
                    'class' => Epreuve::class,
                    'choice_label' => 'nom',
                    'label' => 'Épreuve',
                    'attr' => ['class' => 'form-select'],
                ])
                ->add('elementArtistique', EntityType::class, [
                    'class' => ElementArtistique::class,
                    'choice_label' => 'nom',
                    'label' => 'Élément Artistique',
                    'attr' => ['class' => 'form-select'],
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NoteArtistique::class,
            'include_relations' => false,
        ]);
    }
}
