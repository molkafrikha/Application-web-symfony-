<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lieu')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('datefin', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('titre')
            ->add('description',TextareaType::class, [
        'attr' => ['class' => 'tinymce'],
]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
