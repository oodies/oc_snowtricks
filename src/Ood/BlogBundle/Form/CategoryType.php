<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace Ood\BlogBundle\Form;

use Ood\BlogBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CategoryType
 *
 * @package Ood\BlogBundle\Form
 */
class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name', TextType::class,
                [
                    'required' => true,
                    'label'    => 'category_form.name.label',
                ]
            )
            ->add(
                'description', TextareaType::class,
                [
                    'label' => 'category_form.description.label',
                ]
            )
            ->add(
                'submit', SubmitType::class,
                [
                    'label' => 'category_form.submit.label'
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
                'data_class'         => Category::class,
                'translation_domain' => 'application',
            ]
        );
    }
}
