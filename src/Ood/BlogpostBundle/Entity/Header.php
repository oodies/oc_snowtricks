<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\BlogpostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Header
 *
 * @package Ood\BlogpostBundle\Entity
 *
 * @ORM\Table(name="blogpost_header")
 * @ORM\Entity(repositoryClass="Ood\BlogpostBundle\Repository\HeaderRepository")
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
     *     length=255,
     *     options={
     *      "comment"="Contains the content of the title post"}
     * )
     *
     * @Assert\NotNull(
     *     message="header.title.not_null"
     *     )
     *
     * @Assert\Length(
     *     max=255,
     *     maxMessage="header.title.max_length"
     * )
     */
    protected $title;

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
     *  ASSOCIATION MAPPING
     */

    /**
     * @var Post
     *
     * @ORM\OneToOne(
     *     targetEntity="Ood\BlogpostBundle\Entity\Post",
     *     mappedBy="header",
     *     cascade={"persist"}
     * )
     *
     * @ORM\JoinColumn(
     *     name="post",
     *     referencedColumnName="id_post"
     * )
     *
     * @Assert\NotNull(
     *     message="header.post.not_null"
     * )
     */
    protected $post;

    /** *******************************
     *  CONSTRUCT
     */


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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Header
     */
    public function setTitle(string $title): Header
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getBrief(): string
    {
        return $this->brief;
    }

    /**
     * @param string $brief
     *
     * @return Header
     */
    public function setBrief(string $brief): Header
    {
        $this->brief = $brief;
        return $this;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     *
     * @return Header
     */
    public function setPost(Post $post): Header
    {
        $this->post = $post;
        return $this;
    }
}
