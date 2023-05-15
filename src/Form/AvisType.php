<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use App\Entity\Offrecovoiturage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commenraire', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ intitulé ne doit pas être vide.'
                    ])
                ]])
            ->add('iduser',EntityType::class,
            ['class'=>User::class,
            'choice_label'=>'nom',
            'label'=>'User'])
            ->add('id_offrecov',EntityType::class,
            ['class'=>Offrecovoiturage::class,
            'choice_label'=>'marque',
            'label'=>'offrecovoiturage'])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
