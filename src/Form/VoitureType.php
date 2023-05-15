<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Voiture;
use App\Entity\User;
use phpDocumentor\Reflection\Types\AggregatedType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
Use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('puissance')
            ->add('kilometrage')
            ->add('nbplaces')
            ->add('dateAssurance' , DateType::class, [
                'widget' => 'single_text' , ])
            ->add('dateDVidange' , DateType::class, [
                'widget' => 'single_text' , ])
            ->add('color')
            ->add('marque')

            ->add('idUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'label' => 'User',
                'expanded' => false, // affiche les utilisateurs sous forme de boutons radio
                'multiple' => false,
                ])
                ->add('idagence' , EntityType::class, [
                    'class'=>Agence::class
                    
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
