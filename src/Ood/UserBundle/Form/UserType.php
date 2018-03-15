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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType
 *
 * @package Ood\UserBundle\Form
 */
class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'username', TextType::class,
                [
                    'label'    => 'user_form.username.label',
                    'required' => true
                ]
            )
            ->add(
                'roles', ChoiceType::class,
                [
                    'choices'                   => [
                        'role_user'    => 'ROLE_USER',
                        'role_blogger' => 'ROLE_BLOGGER',
                        'role_admin'   => 'ROLE_ADMIN'
                    ],
                    'expanded'                  => true,
                    'multiple'                  => true,
                    'label'                     => 'user_form.roles.label',
                    'required'                  => true,
                    'choice_translation_domain' => 'configuration'
                ]
            )
            ->add(
                'submit', SubmitType::class,
                [
                    'label' => 'user_form.submit'
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
            ]
        );
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'ood_user_bundle_user_type';
    }
}
