<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogpostBundle\Form;

use Ood\BlogpostBundle\Entity\Post;
use Ood\PictureBundle\Form\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostType
 *
 * @package Ood\BlogpostBundle\Form
 */
class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('header', HeaderType::class)
            ->add('body', BodyType::class)
            ->add(
                'category', EntityType::class,
                [
                    'class'        => 'OodBlogpostBundle:Category',
                    'choice_label' => 'name',
                    'multiple'     => false,
                    'expanded'     => false,
                ]
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'entry_options' => ['label' => false],
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
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
                'data_class'         => Post::class,
                'translation_domain' => 'application',
            ]
        );
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        parent::getBlockPrefix();

        return 'form_post_new';
    }
}
