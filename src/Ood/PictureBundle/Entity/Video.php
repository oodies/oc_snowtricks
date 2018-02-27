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
 * Class Video entity
 *
 * @package Ood\PictureBundle\Entity
 *
 * @ORM\Table(name="picture_video")
 * @ORM\Entity(repositoryClass="Ood\PictureBundle\Repository\VideoRepository")
 */
class Video
{
    /** *******************************
     *  PROPERTIES ORM
     */

    /**
     * The ID of the video
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\column(
     *     name="id_video",
     *     type="integer",
     *     unique=true,
     *     options={"unsigned"=true,
     *              "comment"= "Contains the ID of the video"
     *              }
     * )
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $idVideo;

    /**
     * The video host name
     *
     * @var string
     *
     * @ORM\Column(
     *      name="type",
     *      type="string",
     *      length=255,
     *      options={"comment"="The video host name."}
     * )
     *
     * @Assert\NotBlank(
     *     message="video.platform.not_blank"
     *     )
     *
     * @Assert\Length(
     *     max="255",
     *     maxMessage="video.platform.max_length"
     * )
     */
    protected $platform = "";

    /**
     * The ID of the video resource for this platform
     *
     * @var string
     *
     * @ORM\Column(
     *      name="src",
     *      type="string",
     *      length=255,
     *      options={"comment"="The ID of the video resource for this platform"}
     * )
     *
     * @Assert\NotBlank(
     *     message="video.identifier.not_blank"
     *     )
     *
     * @Assert\Length(
     *     max="255",
     *     maxMessage="video.identifier.max_length"
     * )
     */
    protected $identifier = "";

    /** *******************************
     *      GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getIdVideo(): int
    {
        return $this->idVideo;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     *
     * @return Video
     */
    public function setPlatform(string $platform): Video
    {
        $this->platform = $platform;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     *
     * @return Video
     */
    public function setIdentifier(string $identifier): Video
    {
        $this->identifier = $identifier;
        return $this;
    }
}
