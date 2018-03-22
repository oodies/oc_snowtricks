<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Header
 *
 * @package Ood\BlogBundle\Entity
 *
 * @ORM\Table(name="blogpost_header")
 * @ORM\Entity(repositoryClass="Ood\BlogBundle\Repository\HeaderRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\EntityListeners({"Ood\BlogBundle\EventListener\Entity\HeaderListener"})
 *
 * @UniqueEntity("title", message="header.title.unique_entity")
 */
class Header
{
    /**
     * Contains the ID of the header post
     *
     * @var int
     *
     * @ORM\Column(
     *     name="id_header",
     *     type="integer",
     *     unique=true,
     *     length=11,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the header post"
     *     }
     * )
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $idHeader;

    /**
     * Contains the content of the title post
     *
     * @var string
     *
     * @ORM\Column(
     *     name="title",
     *     type="string",
     *     nullable=false,
     *     unique=true,
     *     length=64,
     *     options={
     *      "comment"="Contains the content of the title post"}
     * )
     *
     * @Assert\NotNull(
     *     message="header.title.not_null"
     *     )
     *
     * @Assert\Length(
     *     max=64,
     *     maxMessage="header.title.max_length"
     * )
     */
    protected $title;

    /**
     * Contains the slug matched title of the header blog
     *
     * @var string|null
     *
     * @ORM\Column(
     *     name="slug",
     *     type="string",
     *     length=128,
     *     nullable=true,
     *     options={"comment"="Contains the slug matched title of the event"}
     * )
     */
    protected $slug;

    /**
     * Contains the content of the brief post
     *
     * @var string
     *
     * @ORM\Column(
     *     name="brief",
     *     type="string",
     *     nullable=false,
     *     length=255,
     *     options={
     *      "comment"="Contains the content of the brief post"}
     * )
     *
     * @Assert\NotNull(
     *     message="header.brief.not_null"
     *     )
     *
     * @Assert\Length(
     *     max=255,
     *     maxMessage="header.brief.max_length"
     * )
     */
    protected $brief;


    /** *******************************
     *  GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getIdHeader(): int
    {
        return $this->idHeader;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return Header
     */
    public function setTitle(?string $title): Header
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param null|string $slug
     *
     * @return Header
     */
    public function setSlug(?string $slug): Header
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBrief(): ?string
    {
        return $this->brief;
    }

    /**
     * @param string|null $brief
     *
     * @return Header
     */
    public function setBrief(?string $brief): Header
    {
        $this->brief = $brief;
        return $this;
    }
}
