<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\PictureBundle\EventListener\Entity;


use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Ood\PictureBundle\Entity\Image;

/**
 * Class ImageListener
 *
 * @package Ood\PictureBundle\EventListener\Entity
 */
class ImageListener
{
    /**
     * The kernel.project_dir value
     *
     * @var string
     */
    protected $projectDir;

    /**
     * The images folder for a browser (relative to the /web folder)
     *
     * @var string
     */
    protected $webBase;

    /**
     * @var string
     */
    protected $tempFilename;


    /**
     * ImageListener constructor.
     *
     * @param string $projectDir
     * @param string $webBase
     */
    public function __construct(string $projectDir, string $webBase)
    {
        $this->projectDir = $projectDir;
        $this->webBase = $webBase;
    }

    /**
     *
     * @ORM\PrePersist()
     *
     * @param Image $image
     */
    public function prePersist(Image $image)
    {
        $this->setExtension($image);
        $this->setAlt($image);
    }

    /**
     * @ORM\PostPersist()
     * @param Image $image
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function postPersist(Image $image)
    {
        $this->upload($image);
    }

    /**
     * @param PreUpdateEventArgs $args
     *
     * @ORM\PreUpdate()
     */
    public function preUpdate(Image $image, PreUpdateEventArgs $args)
    {
        $this->setExtension($image);
        $this->setAlt($image);
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @ORM\PostUpdate()
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function postUpdate(Image $image, LifecycleEventArgs $args)
    {
        $this->upload($image);
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @ORM\PreRemove()
     */
    public function preRemove(Image $image, LifecycleEventArgs $args)
    {
        // I save the filename that is built from the database information
        $this->tempFilename = $this->getUploadRootDir() . '/' . $image->getIdImage() . '.' . $image->getExtension();
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @ORM\PostRemove()
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        if (file_exists($this->tempFilename)) {
            unlink($this->tempFilename);
        }
    }

    /**
     * @param Image $image
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    protected function upload(Image $image)
    {
        // No file to process
        if (null == $image->getFile()) {
            return;
        }

        $image->getFile()->move(
            $this->getUploadRootDir(),
            $image->getIdImage() . '.' . $image->getExtension()
        );
    }

    /**
     * @param $image
     */
    protected function setExtension(Image $image)
    {
        $image->setExtension($image->getFile()->guessExtension());
    }

    /**
     * @param Image $image
     */
    protected function setAlt(Image $image)
    {
        $image->setAlt($image->getFile()->getClientOriginalName());
    }

    /**
     * Images folder for a browser (relative to the /web folder)
     *
     * @return string
     */
    protected function getWebBase()
    {
        return $this->webBase;
    }

    /**
     * Images folder for backend code
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        return $this->projectDir . '/web/' . $this->getWebBase();
    }
}
