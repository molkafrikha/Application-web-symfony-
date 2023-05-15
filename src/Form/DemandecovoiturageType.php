<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\User;
use App\Entity\Demandecovoiturage;
use App\Entity\Offrecovoiturage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use DateTime;

class DemandecovoiturageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateReservation', DateTimeType::class, [
                'data' => $options['dateReservation'], // Pass the default value to the field
            ])
            ->add('nbPlace')
            ->add('idUser',EntityType::class,
                ['class'=>User::class,
                    'choice_label'=>'nom',
                    'label'=>'User'
                ])
            ->add('idOffre',EntityType::class,
            ['class'=>Offrecovoiturage::class,
                'choice_label'=>'matricule',
                'label'=>'offre'
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demandecovoiturage::class,
            'dateReservation' => new DateTime(),
        ]);
    }
}
