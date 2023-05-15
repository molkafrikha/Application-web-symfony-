<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Maintenance;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Voiture;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MaintenanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('idVoi', EntityType::class, [
                'class' => Voiture::class,
                'choice_label' => 'matricule',
                'label' => 'Voiture',
                'expanded' => false, // affiche les utilisateurs sous forme de boutons radio
                'multiple' => false,
            ])
            ->add('dateDAssurance', DateType::class, [
                'widget' => 'single_text' , ])
            ->add('dateDVidange' , DateType::class, [
                'widget' => 'single_text' , ] )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Maintenance::class,
        ]);
    }
}
