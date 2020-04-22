<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class ServiciosType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('servicio' , TextType::class , [
            'attr'=>['class'=>'form-control'],
            'label' => 'Servicio'
        ])
        ->add('costo' , NumberType::class , [
            'attr'=>['class'=>'form-control',
            'type'=>'phone'],
            'label' => 'Costo'           
        ])   
        ->add('tipo' , ChoiceType::class , [
            'attr'=>['class'=>'form-control btn btn-secondary dropdown-toggle'],
            'label' => 'Tipo',
            'choices' => [
                'Lavanderia' => 'lavanderia',
                'Tienda' => 'tienda',
                'Material Roto' => 'roto'
            ]
        ])     
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Servicios'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_Servicios';
    }


}
