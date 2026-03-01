<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ElementTechniqueImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'Fichier Excel (.xlsx, .xls)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '2M',
                        mimeTypes: [
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ],
                        mimeTypesMessage: 'Veuillez uploader un fichier Excel valide',
                    ),
                ],
            ])
            ->add('googleSheetUrl', UrlType::class, [
                'label' => 'Ou URL Google Sheet (doit être publié ou accessible au web)',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'https://docs.google.com/spreadsheets/d/...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
