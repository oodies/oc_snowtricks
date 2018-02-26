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
 * Class Embed entity
 *
 * @package Ood\PictureBundle\Entity
 *
 * @ORM\Table(name="picture_embed")
 * @ORM\Entity(repositoryClass="Ood\PictureBundle\Repository\EmbedRepository")
 */
class Embed
{
    /** *******************************
     *  PROPERTIES ORM
     */

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\column(
     *     name="id_embed",
     *     type="integer",
     *     unique=true,
     *     options={"unsigned"=true}
     * )
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $idEmbed;

    /**
     * The MIME type to use to select the plug-in to instantiate.
     *
     * @var string
     *
     * @ORM\Column(
     *      name="type",
     *      type="string",
     *      length=255,
     *      options={"comment"="The MIME type to use to select the plug-in to instantiate."}
     * )
     *
     * @Assert\NotBlank(
     *     message="embed.type.not_blank"
     *     )
     *
     * @Assert\Length(
     *     max="255",
     *     maxMessage="embed.type.max_length"
     * )
     */
    protected $type = "";

    /**
     * The URL of the resource being embedded.
     *
     * @var string
     *
     * @ORM\Column(
     *      name="src",
     *      type="string",
     *      length=255,
     *      options={"comment"="The URL of the resource being embedded."}
     * )
     *
     * @Assert\NotBlank(
     *     message="embed.src.not_blank"
     *     )
     *
     * @Assert\Length(
     *     max="255",
     *     maxMessage="embed.src.max_length"
     * )
     */

    protected $src = "";

    /** *******************************
     *      GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getIdEmbed(): int
    {
        return $this->idEmbed;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Embed
     */
    public function setType(string $type): Embed
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @param string $src
     *
     * @return Embed
     */
    public function setSrc(string $src): Embed
    {
        $this->src = $src;
        return $this;
    }
}
