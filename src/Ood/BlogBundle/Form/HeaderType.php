<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogBundle\Form;

use Ood\BlogBundle\Entity\Header;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class HeaderType
 *
 * @package Ood\BlogBundle\Form
 */
class HeaderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title', TextType::class,
                [
                    'required' => true,
                    'label'    => 'posts_form.title.label',
                ]
            )
            ->add(
                'brief', TextType::class,
                [
                    'required' => true,
                    'label'    => 'posts_form.brief.label',
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
                'data_class'         => Header::class,
                'translation_domain' => 'application',
            ]
        );
    }
}
