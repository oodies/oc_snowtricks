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
 * Class Body
 *
 * @package Ood\BlogpostBundle\Entity
 *
 * @ORM\Table(name="blogpost_body")
 * @ORM\Entity(repositoryClass="Ood\BlogpostBundle\Repository\BodyRepository")
 */
class Body
{
    /**
     * Contains the ID of the content post
     *
     * @var int
     *
     * @ORM\Column(
     *     name="id_body",
     *     type="integer",
     *     unique=true,
     *     length=11,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the content post"
     *     }
     * )
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $idBody;

    /**
     * Contains the content of the post
     *
     * @var string
     *
     * @ORM\Column(
     *     name="content",
     *     type="text",
     *     nullable=true,
     *     options={
     *      "comment"="Contains the content of the post"}
     * )
     */
    protected $content;

    /**
     * @var Post
     *
     * @ORM\OneToOne(
     *     targetEntity="Ood\BlogpostBundle\Entity\Post",
     *     cascade={"persist"}
     * )
     *
     * @ORM\JoinColumn(
     *     name="post",
     *     referencedColumnName="id_post"
     * )
     *
     * @Assert\NotNull(
     *     message="body.post.not_null"
     *     )
     */
    protected $post;


    /** *******************************
     *  GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getIdBody(): int
    {
        return $this->idBody;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Body
     */
    public function setContent(string $content): Body
    {
        $this->content = $content;
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
     * @return Body
     */
    public function setPost(Post $post): Body
    {
        $this->post = $post;
        return $this;
    }
}
