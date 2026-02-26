<?php

namespace App\Form;

use App\Entity\ElementTechnique;
use App\Entity\Epreuve;
use App\Entity\NoteTechnique;
use App\Entity\Patineuse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteTechniqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', NumberType::class, [
                'label' => 'note',
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
                    'placeholder' => 'Choisir une patineuse',
                ])
                ->add('epreuve', EntityType::class, [
                    'class' => Epreuve::class,
                    'choice_label' => 'nom',
                    'label' => 'Épreuve',
                    'attr' => ['class' => 'form-select'],
                    'placeholder' => 'Choisir une épreuve',
                ])
                ->add('elementTechnique', EntityType::class, [
                    'class' => ElementTechnique::class,
                    'choice_label' => 'nom',
                    'label' => 'Élément Technique',
                    'attr' => ['class' => 'form-select'],
                    'placeholder' => 'Choisir un élément',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NoteTechnique::class,
            'include_relations' => false,
        ]);
    }
}
