<?php

namespace App\Form;

use App\Entity\Competition;
use App\Entity\Engagement;
use App\Entity\Patineuse;
use App\Repository\AnneeNaissanceRepository;
use App\Repository\EpreuveRepository;
use App\Repository\PatineuseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EngagementType extends AbstractType
{
    public function __construct(
        private readonly AnneeNaissanceRepository $anneeNaissanceRepository,
        private readonly EpreuveRepository $epreuveRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $competition = $options['competition'];

        $builder
            ->add('patineuse', EntityType::class, [
                'class' => Patineuse::class,
                'query_builder' => function (PatineuseRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.prenom', 'ASC')
                        ->addOrderBy('p.nom', 'ASC');
                },
                'choice_label' => function (Patineuse $patineuse) {
                    return $patineuse->getPrenom().' '.$patineuse->getNom();
                },
                'label' => 'Patineuse',
                'attr' => [
                    'class' => 'form-select',
                ],
                'placeholder' => 'Choisir une patineuse',
            ])
        ;

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) use ($competition) {
                $this->validateEpreuve($event->getForm(), $event->getData(), $competition);
            }
        );
    }

    private function validateEpreuve(FormInterface $form, ?Engagement $data, ?Competition $competition): void
    {
        if (!$data) {
            return;
        }

        $patineuse = $data->getPatineuse();

        if (!$patineuse) {
            return;
        }

        $categorieAnnee = $this->anneeNaissanceRepository->findOneBy(['annee' => $patineuse->getAnneeDeNaissance()]);
        $categorie = $categorieAnnee?->getCategorie();
        $epreuves = [];
        if ($categorie && $competition) {
            $epreuves = $this->epreuveRepository->findBy([
                'niveau' => $patineuse->getNiveau(),
                'categorie' => $categorie,
                'competition' => $competition,
            ]);
        }

        if ($epreuves) {
            $data->setEpreuve($epreuves[0]);
        } else {
            $form->get('patineuse')->addError(
                new FormError('Aucune épreuve disponible pour cette patineuse dans cette compétition.')
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Engagement::class,
            'competition' => null,
        ]);
    }
}
