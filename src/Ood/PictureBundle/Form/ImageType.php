<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\PictureBundle\Form;

use Ood\PictureBundle\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image as ValidatorImage;

/**
 * Class ImageType
 *
 * @package
 */
class ImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'file', FileType::class,
                [
                    'required'    => false,
                    'attr'        => [
                        'accept' => 'image/*;capture=camcorder',
                        'class'  => 'input_file'
                    ],
                    'label'       => 'image_form.file.label',
                    'label_attr'  => [
                        'class' => 'input_file-trigger',
                    ],
                    'constraints' => [
                        new ValidatorImage(
                            [
                                'maxSize'        => '1M',
                                'maxSizeMessage' => 'Maximum 1M file size'
                            ]
                        )
                    ]
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
                'data_class'         => Image::class,
                'translation_domain' => 'application',
            ]
        );
    }
}
