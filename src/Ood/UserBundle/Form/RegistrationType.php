<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\UserBundle\Form;

use Ood\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationType
 *
 * @package Ood\UserBundle\Form
 */
class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email', EmailType::class,
                [
                    'label'    => 'registration.email.label',
                    'required' => true
                ]
            )
            ->add(
                'username', TextType::class,
                [
                    'label'    => 'registration.username.label',
                    'required' => true
                ]
            )
            ->add(
                'plainPassword', RepeatedType::class, [
                                   'type'           => PasswordType::class,
                                   'required'       => true,
                                   'first_options'  => [
                                       'label' => 'registration.plain_password.label'
                                   ],
                                   'second_options' => [
                                       'label' => 'registration.plain_password_repeat.label'
                                   ]
                               ]
            )
            ->add(
                'submit', SubmitType::class,
                [
                    'label' => 'registration.submit.label'
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => User::class,
                'translation_domain' => 'application',
                'attr'               => ['id' => 'form-register'],
                'validation_groups'  => [$this, 'getValidationGroups']
            ]
        );
    }

    /**
     * Obtain validation groups according to data form
     *
     * @return array
     */
    public function getValidationGroups(): array
    {
        return ['registration'];
    }
}
