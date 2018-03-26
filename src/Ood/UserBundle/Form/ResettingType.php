<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\UserBundle\Form;

use Ood\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ResettingType
 *
 * @package Ood\UserBundle\Form
 */
class ResettingType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'plainPassword', RepeatedType::class, [
                                   'type'           => PasswordType::class,
                                   'required'       => true,
                                   'first_options'  => [
                                       'label' => 'registration.plain_password.label',
                                   ],
                                   'second_options' => [
                                       'label' => 'registration.plain_password_repeat.label',
                                   ],
                                   'constraints' => new NotBlank(
                                       ['message' => 'user.plainPassword.not_blank']
                                   )
                               ]
            )
            ->add(
                'submit', SubmitType::class,
                [
                    'label' => 'resetting_reset.submit.label',
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
                'translation_domain' => 'application'
            ]
        );
    }
}
