<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogBundle\Form;

use Ood\BlogBundle\Entity\Post;
use Ood\PictureBundle\Form\ImageType;
use Ood\PictureBundle\Form\VideoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostType
 *
 * @package Ood\BlogBundle\Form
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
                    'class'        => 'OodBlogBundle:Category',
                    'choice_label' => 'name',
                    'label'        => 'posts_form.category.label',
                    'required'     => false,
                    'multiple'     => false,
                    'expanded'     => false,
                ]
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type'    => ImageType::class,
                    'entry_options' => ['label' => false],
                    'by_reference'  => false,
                    'allow_add'     => true,
                    'allow_delete'  => true,
                ]
            )
            ->add(
                'videos',
                CollectionType::class,
                [
                    'entry_type'    => VideoType::class,
                    'entry_options' => ['label' => false],
                    'by_reference'  => false,
                    'allow_add'     => true,
                    'allow_delete'  => true,
                ]
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'onPreSubmit']
            )
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onPostSubmit']
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
                'translation_domain' => 'application'
            ]
        );
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        parent::getBlockPrefix();

        return 'form_post';
    }


    /**
     * @param FormEvent $formEvent
     */
    public function onPreSubmit(FormEvent $formEvent)
    {
        $post = $formEvent->getData();

        if (isset($post['videos'])) {
            foreach ($post['videos'] as $key => $video) {
                if (empty($video['url'])) {
                    unset($post['videos'][$key]);
                }
            }
        }

        $formEvent->setData($post);
    }

    /**
     * @param FormEvent $formEvent
     */
    public function onPostSubmit(FormEvent $formEvent)
    {
        /** @var Post $post */
        $post = $formEvent->getData();

        foreach ($post->getImages() as $image) {
            if (null === $image->getIdImage() && null === $image->getFile()) {
                $post->removeImage($image);
            }
        }

        $formEvent->setData($post);
    }
}
