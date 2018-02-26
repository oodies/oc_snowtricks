<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\PictureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Image
 *
 * @package Ood\PictureBundle\Entity
 *
 * @ORM\Table(name="picture_image")
 * @ORM\Entity(repositoryClass="Ood\PictureBundle\Repository\ImageRepository")
 */
class Image
{
    /*
     * *******************************
     * PROPERTIES ORM
     * ******************************
     */

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
    private $idImage;

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
     *
     * @Assert\NotBlank(
     *     message="image.extension.not_blank"
     *     )
     *
     * @Assert\Length(
     *     max="255",
     *     maxMessage="image.extension.max_length"
     * )
     */
    private $extension = "";

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
     *
     * @Assert\NotBlank(
     *     message="image.alt.not_blank"
     *     )
     *
     * @Assert\Length(
     *     max="255",
     *     maxMessage="image.alt.max_length"
     * )
     */
    private $alt = "";

    /*
     * *******************************
     * GETTER / SETTER
     * ******************************
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
}
