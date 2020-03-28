<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ReservaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('menor',NumberType::class,[
            'label' => 'Menores de edad',
        ])
        ->add('adulto',NumberType::class,[
            'label' => 'Mayores de edad',
        ])
        ->add('llegada', DateType::class, [
            'label' => 'Fecha de llegada',
            'widget'=> 'single_text',   
        ])
        ->add('salida', DateType::class, [
            'label' => 'Fecha de salida',
            'widget'=> 'single_text',   
        ])
        ->add('primeraComida', ChoiceType::class,[
            'label'=>'primeraComida',
            'choices' =>
            [
            'desayuno'=>'desayuno',
            'almuerzo'=> 'almuerzo',
            'cena'=>'cena',
            ],
            'multiple'=> false,
        ])
        ->add('ultimaComida', ChoiceType::class,[
            'label'=>'ultimaComida',
            'choices' =>
            [
            'desayuno'=>'desayuno',
            'almuerzo'=> 'almuerzo',
            'cena'=>'cena',
            ],
            'multiple'=> false,
            
        ])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Reserva'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_reserva';
    }


}
