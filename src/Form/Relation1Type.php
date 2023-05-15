<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Relation1;
use App\Entity\Sponsoring;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Relation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_sponsor',EntityType::class,
            ['class'=>Sponsoring::class,
                'choice_label'=>'sponsor',
                'label'=>'Le sponosr'
            ])
           /* ->add('id_evenement',EntityType::class,
                ['class'=>Evenement::class,
                    'choice_label'=>'titre',
                    'label'=>'L \'évenement'
                ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Relation1::class,
        ]);
    }
}
