<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\BlogpostBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ood\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Post
 *
 * @package Ood\BlogpostBundle\Entity
 *
 * @ORM\Table(name="blogpost_post")
 * @ORM\Entity(repositoryClass="Ood\BlogpostBundle\Repository\PostRepository")
 */
class Post
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * Contains the ID of the post
     *
     * @var int
     *
     * @ORM\Column(
     *     name="id_post",
     *     type="integer",
     *     unique=true,
     *     length=11,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the post"
     *      }
     * )
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $idPost;

    /**
     * Contains the date of creation of the post
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="create_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Contains the date of creation of the post"}
     * )
     *
     * @Assert\DateTime(
     *     message="post.createAt.not_validate"
     * )
     */
    protected $createAt;

    /**
     * Contains the update date of the post
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="update_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Contains the update date of the post"}
     * )
     *
     * @Assert\DateTime(
     *     message="post.updateAt.not_validate"
     * )
     */
    protected $updateAt;

    /** *******************************
     *  ASSOCIATION MAPPING
     */

    /**
     * @var Body
     *
     * @ORM\OneToOne (
     *     targetEntity="Ood\BlogpostBundle\Entity\Body",
     *     cascade={"persist"}
     * )
     *
     * @ORM\JoinColumn(
     *     name="body",
     *     referencedColumnName="id_body"
     *     )
     *
     * @Assert\NotNull(
     *     message="post.body.not_null"
     *     )
     */
    protected $body;

    /**
     * @var Header
     *
     * @ORM\OneToOne (
     *     targetEntity="Ood\BlogpostBundle\Entity\Header",
     *     mappedBy="post",
     *     cascade={"persist"}
     * )
     *
     * @ORM\JoinColumn(
     *     name="header",
     *     referencedColumnName="id_header"
     *     )
     *
     * @Assert\NotNull(
     *     message="post.header.not_null"
     *     )
     */
    protected $header;

    /**
     * @var User
     *
     * @ORM\ManyToOne(
     *     targetEntity="Ood\UserBundle\Entity\User",
     *     cascade={"persist"}
     * )
     *
     * @ORM\JoinColumn(name="blogger", referencedColumnName="id_user")
     *
     * @Assert\NotNull(
     *     message="post.blogger.not_null"
     * )
     */
    protected $blogger;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(
     *     targetEntity="Ood\BlogpostBundle\Entity\Category",
     *     cascade={"persist"}
     * )
     *
     * @ORM\JoinColumn(
     *     name="category",
     *     referencedColumnName="id_category"
     * )
     *
     * @Assert\NotNull(
     *     message="body.category.not_null"
     *     )
     */
    protected $category;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Ood\PictureBundle\Entity\Image")
     * @ORM\JoinTable(name="posts_images",
     *      joinColumns={
     *              @ORM\JoinColumn(name="post_id",
     *              referencedColumnName="id_post")
     *      },
     *      inverseJoinColumns={
     *              @ORM\JoinColumn(name="image_id",
     *              referencedColumnName="id_image")
     *      }
     * )
     */
    protected $images;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Ood\PictureBundle\Entity\Embed")
     * @ORM\JoinTable(name="posts_embeds",
     *      joinColumns={
     *              @ORM\JoinColumn(name="post_id",
     *              referencedColumnName="id_post")
     *      },
     *      inverseJoinColumns={
     *              @ORM\JoinColumn(name="embed_id",
     *              referencedColumnName="id_embed")
     *      }
     * )
     */
    protected $embeds;


    /** *******************************
     *  CONSTRUCT
     */

    public function __construct()
    {
        $dateAt = new \DateTime();
        $this->setCreateAt($dateAt)
             ->setUpdateAt($dateAt);

        $this->images = new ArrayCollection();
        $this->embeds = new ArrayCollection();
    }


    /** *******************************
     *  GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getIdPost(): int
    {
        return $this->idPost;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt(): \DateTime
    {
        return $this->createAt;
    }

    /**
     * @param \DateTime $createAt
     *
     * @return Post
     */
    public function setCreateAt(\DateTime $createAt): Post
    {
        $this->createAt = $createAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateAt(): \DateTime
    {
        return $this->updateAt;
    }

    /**
     * @param \DateTime $updateAt
     *
     * @return Post
     */
    public function setUpdateAt(\DateTime $updateAt): Post
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    /**
     * @return User
     */
    public function getBlogger(): User
    {
        return $this->blogger;
    }

    /**
     * @param User $blogger
     *
     * @return Post
     */
    public function setBlogger(User $blogger): Post
    {
        $this->blogger = $blogger;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return Post
     */
    public function setCategory(Category $category): Post
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Body
     */
    public function getBody(): Body
    {
        return $this->body;
    }

    /**
     * @param Body $body
     *
     * @return Post
     */
    public function setBody(Body $body): Post
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return Header
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * @param Header $header
     *
     * @return Post
     */
    public function setHeader(Header $header): Post
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @param \Ood\PictureBundle\Entity\Image $image
     *
     * @return Post
     */
    public function addImage(\Ood\PictureBundle\Entity\Image $image): Post
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * @param \Ood\PictureBundle\Entity\Image $image
     *
     * @return void
     */
    public function removeImage(\Ood\PictureBundle\Entity\Image $image): void
    {
        $this->images->removeElement($image);
    }

    /**
     * @return ArrayCollection
     */
    public function getImages(): ArrayCollection
    {
        return $this->images;
    }

    /**
     * @param \Ood\PictureBundle\Entity\Embed $embed
     *
     * @return Post
     */
    public function addEmbed(\Ood\PictureBundle\Entity\Embed $embed): Post
    {
        $this->embeds[] = $embed;

        return $this;
    }

    /**
     * @param \Ood\PictureBundle\Entity\Embed $embed
     *
     * @return void
     */
    public function removeEmbed(\Ood\PictureBundle\Entity\Embed $embed): void
    {
        $this->embeds->removeElement($embed);
    }

    /**
     * @return ArrayCollection
     */
    public function geEmbeds(): ArrayCollection
    {
        return $this->embeds;
    }
}
