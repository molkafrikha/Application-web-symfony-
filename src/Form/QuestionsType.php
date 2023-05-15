<?php
namespace App\Form;


use App\Entity\Sondage;
use App\Entity\Questions;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
/* ->add('question')
            ->add('type')
            ->add('sondage',EntityType::class,
            ['class'=>Sondage::class,
                'choice_label'=>'sujet',
                'label'=>'sondage']);
        ;*/
        
            ->add('question', TextType::class )

            ->add('type', ChoiceType::class,[
                'expanded' => true,
                'choices' => [
                    'Text'=>"Text",
                    'Rate' =>"Rate",
                    'YES/NO'=>"YES/NO",
                   
                
                #],
                #'attr'=>[
                   # 'style'=>'display : flex; flex-direction: row-reverse; align-items: flex-start; justify-content : center; ',
                ]
                                ]);
          

          
        
                }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
