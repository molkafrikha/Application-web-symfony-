<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Offrecovoiturage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class OffrecovoiturageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('marque')
            ->add('dated', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('lieud', ChoiceType::class, [
                'choices'=> [
                    'Ariana' => 'Ariana',
                    'Beja' => 'Beja',
                    'Ben Arous' => 'Ben Arous',
                    'Bizerte' => 'Bizerte',
                    'Gabes' => 'Gabes',
                    'Gafsa' => 'Gafsa',
                    'Jendouba' => 'Jendouba',
                    'Kairouan' => 'Kairouan',
                    'kasserine' => 'kasserine',
                    'Kebili' => 'Kebili',
                    'Kef' => 'Kef',
                    'Mahdia' => 'Mahdia',
                    'Manouba' => 'Manouba',
                    'Medenine' => 'Medenine',
                    'Monastir' => 'Monastir',
                    'Nabeul' => 'Nabeul',
                    'Sfax' => 'Sfax',
                    'Sidi Bouzid' => 'Sidi Bouzid',
                    'Siliana' => 'Siliana',
                    'Sousse' => 'Sousse',
                    'Tataouine' => 'Tataouine',
                    'Tozeur' => 'Tozeur',
                    'Tunis' => 'Tunis',
                    'Zaghouan' => 'Zaghouan',


                ],
            ])
            ->add('lieua', ChoiceType::class, [
        'choices'=> [
            'Ariana' => 'Ariana',
            'Beja' => 'Beja',
            'Ben Arous' => 'Ben Arous',
            'Bizerte' => 'Bizerte',
            'Gabes' => 'Gabes',
            'Gafsa' => 'Gafsa',
            'Jendouba' => 'Jendouba',
            'Kairouan' => 'Kairouan',
            'kasserine' => 'kasserine',
            'Kebili' => 'Kebili',
            'Kef' => 'Kef',
            'Mahdia' => 'Mahdia',
            'Manouba' => 'Manouba',
            'Medenine' => 'Medenine',
            'Monastir' => 'Monastir',
            'Nabeul' => 'Nabeul',
            'Sfax' => 'Sfax',
            'Sidi Bouzid' => 'Sidi Bouzid',
            'Siliana' => 'Siliana',
            'Sousse' => 'Sousse',
            'Tataouine' => 'Tataouine',
            'Tozeur' => 'Tozeur',
            'Tunis' => 'Tunis',
            'Zaghouan' => 'Zaghouan',


                ],
            ])
            ->add('dispo', ChoiceType::class, [
                'choices'=>[
                    'oui' => 'oui',
                    'non' => 'non',
                ],
            ])
            ->add('nbplace')
            ->add('numtel')
            ->add('distance')
            ->add('idUser',EntityType::class,
                ['class'=>User::class,
                    'choice_label'=>'nom',
                    'label'=>'User',
                ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offrecovoiturage::class,
        ]);
    }
}
