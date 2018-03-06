<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\PictureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Image
 *
 * @package Ood\PictureBundle\Entity
 *
 * @ORM\Table(name="picture_image")
 * @ORM\Entity(repositoryClass="Ood\PictureBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /* *******************************
     * PROPERTIES ORM
     **/

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(
     *     name="id_image",
     *     type="integer",
     *     unique=true,
     *     options={"unsigned"=true}
     * )
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $idImage;

    /**
     * The image file name extension
     *
     * @var string
     *
     * @ORM\Column(
     *     name="extension",
     *     type="string",
     *     length=255,
     *     options={"comment"="The image file name extension"}
     * )
     */
    protected $extension;

    /**
     * The alternative text describing the image to attribute alt of the HTML <img> element.
     *
     * @var string
     *
     * @ORM\Column(
     *     name="alt",
     *     type="string",
     *     length=255,
     *     options={"comment"="The alternative text describing the image to attribute alt of the HTML <img> element."}
     * )
     */
    protected $alt;

    /* *******************************
     * OTHERS PROPERTIES
     */

    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var string
     */
    protected $tempFilename;

    /* *******************************
     * GETTER / SETTER
     */

    /**
     * Get idImage
     *
     * @return int
     */
    public function getIdImage(): int
    {
        return $this->idImage;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return Image
     */
    public function setExtension(string $extension): Image
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt(string $alt): Image
    {
        $this->alt = $alt;

        return $this;
    }

    /* *******************************
     * METHODS
     */

    /**
     * get file
     *
     * @return null|UploadedFile
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * set file
     *
     * @param null|UploadedFile $file
     */
    public function setFile(?UploadedFile $file = null)
    {
        $this->file = $file;
        // Reset attributes
        $this->extension = null;
        $this->alt = null;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null != $this->file) {
            $this->extension = $this->file->guessExtension();
            $this->alt = $this->file->getClientOriginalName();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function upload()
    {
        // No file to process
        if (null == $this->file) {
            return;
        }

        $this->file->move(
            $this->getUploadRootDir(),
            $this->idImage . '.' . $this->extension
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveFile()
    {
        // I save the filename that is built from the database information
        $this->tempFilename = $this->getUploadRootDir() . '/' . $this->idImage . '.' . $this->extension;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeFile()
    {
        if (file_exists($this->tempFilename)) {
            unlink($this->tempFilename);
        }
    }

    /**
     * Image url for a browser (relative to the /web folder)
     *
     * @return string
     */
    public function getWebPath()
    {
        return $this->getWebBase() . '/' . $this->getIdImage() . '.' . $this->getExtension();
    }

    /**
     * Images folder for a browser (relative to the /web folder)
     *
     * @return string
     */
    protected function getWebBase()
    {
        return 'img/picture';
    }

    /**
     * Images folder for backend code
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        // TODO I Should use Kernel.project_dir
        return dirname(dirname(dirname(dirname(__DIR__)))) . '/web/' . $this->getWebBase();
    }
}
