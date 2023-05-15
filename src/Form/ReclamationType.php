<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\DateType ;



class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ intitulé ne doit pas être vide.'
                    ])
                ]])
            ->add('contenu', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ contenu ne doit pas être vide.'
                    ])
                ]])
            ->add('iduser',EntityType::class,
            ['class'=>User::class,
            'choice_label'=>'nom',
            'label'=>'User'])
            
            ->add('image',FileType::class,[
                'label' => 'image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2Mi',
                        'mimeTypesMessage' => 'Télécharger une image valide',
                    ])
                ],   
            ])
            ->add('date',DateType::class, [
                'widget'=>'single_text',
                'required' => true,
                'data'=> new \DateTime(),
                'attr' => [
                'min' => (new \DateTime())->format('Y-m-d')
              ]
              ])
            
        ;
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
