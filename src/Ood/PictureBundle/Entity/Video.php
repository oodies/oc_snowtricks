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
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Video
{

    /** *******************************
     *  CONSTANT
     */
    // Platforms
    const DAILYMOTION = "dailymotion";
    const VIMEO = "vimeo";
    const YOUTUBE = "youtube";

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
     *      name="platform",
     *      type="string",
     *      length=255,
     *      options={"comment"="The video host name."}
     * )
     *
     */
    protected $platform;

    /**
     * The ID of the video resource for this platform
     *
     * @var string
     *
     * @ORM\Column(
     *      name="identifier",
     *      type="string",
     *      length=255,
     *      options={"comment"="The ID of the video resource for this platform"}
     * )
     *
     */
    protected $identifier;

    /**
     * The tiny URL of the video
     *
     * @var null|string
     *
     * @ORM\Column(
     *     name="url",
     *     type="string",
     *     length=255,
     *     nullable=false,
     *     options={"comment"=""}
     * )
     *
     * @Assert\NotNull(
     *     message="video.url.not_null"
     *     )
     *
     * @Assert\Length(
     *     max="255",
     *     maxMessage="video.url.max_length"
     * )
     *
     * *
     * @Assert\Regex(
     *     pattern="#^(http|https):\/\/(youtu.be|dai.ly|vimeo.com)\/#",
     *     match=true,
     *     message="video.url.regex"
     * )
     *
     */
    protected $url;

    /** *******************************
     *      PROPERTIES
     */

    protected $embed;


    /**
     * Video constructor.
     */
    public function __construct()
    {
        // Extract Identifier and Platform value from Url for new object
        $this->extractIdentifier();
    }

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
     * @return null|string
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    /**
     * @param null|string $platform
     *
     * @return Video
     */
    public function setPlatform(?string $platform): Video
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

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     *
     * @return $this
     */
    public function setUrl(?string $url): Video
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Return embed element used by view
     *
     * @return string
     */
    public function getEmbed(): string
    {
        if ($this->embed === null) {
            $this->setEmbed();
        }
        return $this->embed;
    }

    /**
     * Set embed property on Doctrine events PosLoad
     *
     * @ORM\PostLoad()
     *
     * @return Video
     */
    public function setEmbed(): Video
    {
        $this->embed = '<iframe width="100%" height="100%" src="' . $this->getUrlVideo(
            ) . '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';

        return $this;
    }

    /**
     * Extract Identifier and Platform value from Url
     * on Doctrine events PrePersist and PreUpdate
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function extractIdentifier()
    {
        if (preg_match('#^(https|http)://youtu\.be/(.*)#', $this->url, $matches)) {
            $this->identifier = $matches[2];
            $this->platform = self::YOUTUBE;
        } elseif (
        preg_match('#^(https|http)://vimeo.com/(.*)#', $this->url, $matches)) {
            $this->identifier = $matches[2];
            $this->platform = self::VIMEO;
        } elseif (
        preg_match('#^(https|http)://dai.ly/(.*)#', $this->url, $matches)) {
            $this->identifier = $matches[2];
            $this->platform = self::DAILYMOTION;
        }

        // to force its value to be updated
        $this->embed = null;

        return $this;
    }

    /**
     * URL to th value "src" attribute of the <iframe> element
     * according to platform
     *
     * @return string
     */
    protected function getUrlVideo(): string
    {
        switch ($this->getPlatform()) {
            case self::YOUTUBE:
                $url = 'https://www.youtube.com/embed/' . $this->getIdentifier();
                break;
            case self::DAILYMOTION:
                $url = 'https://www.dailymotion.com/embed/video/' . $this->getIdentifier();
                break;
            case self::VIMEO:
                $url = 'https://player.vimeo.com/video/' . $this->getIdentifier();
                break;
            default:
                $url = '';
                break;
        }

        return $url;
    }
}
