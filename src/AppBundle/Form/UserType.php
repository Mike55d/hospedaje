<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username' , TextType::class , [
            'attr'=>['class'=>'form-control'],
            'label' => 'Usuario'
        ])
        ->add('name' , TextType::class , [
            'attr'=>['class'=>'form-control'],
            'label' => 'Nombre'
        ])
        ->add('grupo', EntityType::class, [
            'class' => 'AppBundle:Grupo',
            'choice_label' => 'nombre',
            'label' => 'Grupo',
            'attr' => ['class'=>'form-control'],
        ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'password-field form-control']],
            'required' => true,
            'first_options'  => ['label' => 'Contraseña'],
            'second_options' => ['label' => 'Repetir Contraseña'],
        ])
        ->add('phone' , TextType::class , [
            'attr'=>['class'=>'form-control'],
            'label' => 'Telefono'
        ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
