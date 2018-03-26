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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResettingRequestType
 *
 * @package Ood\UserBundle\Form
 */
class ResettingRequestType extends AbstractType
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
                    'label'    => 'resetting_request.username.label',
                    'required' => true
                ]
            )
            ->add(
                'submit', SubmitType::class,
                [
                    'label' => 'resetting_request.submit.label',
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
        return ['resettingRequest'];
    }
}
