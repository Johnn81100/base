<?php

namespace App\Form;

use App\Entity\Exemple;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExempleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'attr' => ['class' => 'input_text'],
                'label' => 'Saisir le nom :',
                'label_attr' => ['class' => 'label_text'],
                'required' => true
            ])
            ->add('value', IntegerType::class,[
                'attr' => ['class' => 'input_number'],
                'label' => 'Saisir un nombre :',
                'label_attr' => ['class' => 'label_number'],
                'required' => true]
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exemple::class,
        ]);
    }
}
