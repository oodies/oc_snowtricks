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
use Symfony\Component\Validator\Constraints\NotBlank;

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
                    'required' => true,
                    'attr'     => ['placeholder' => 'registration.email.label']
                ]
            )
            ->add(
                'username', TextType::class,
                [
                    'label'    => 'registration.username.label',
                    'required' => true,
                    'attr'     => ['placeholder' => 'registration.username.label']
                ]
            )
            ->add(
                'plainPassword', RepeatedType::class, [
                                   'type'           => PasswordType::class,
                                   'options'        => ['attr' => ['class' => 'password-field']],
                                   'required'       => true,
                                   'first_options'  => [
                                       'label' => 'registration.plain_password.label',
                                       'attr'  => ['placeholder' => 'registration.plain_password.label']
                                   ],
                                   'second_options' => [
                                       'label' => 'registration.plain_password_repeat.label',
                                       'attr'  => ['placeholder' => 'registration.plain_password_repeat.label']
                                   ],
                                   'constraints' => new NotBlank(
                                       ['message' => 'user.plainPassword.not_blank']
                                   )
                               ]
            )
            ->add(
                'submit', SubmitType::class,
                [
                    'label' => 'registration.submit.label',
                    'attr'  => ['class' => 'btn btn-lg btn-primary btn-block']
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
                'attr'               => ['id' => 'form-register']
            ]
        );
    }
}
