<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ood\PictureBundle\Entity\Image;
use Ood\UserBundle\Entity\User as Blogger;
use Ood\PictureBundle\Entity\Video;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Post
 *
 * @package Ood\BlogBundle\Entity
 *
 * @ORM\Table(name="blog_post",
 *            indexes={@ORM\Index(name="IDX_uniqueID", columns={"unique_id"} ) } )
 * @ORM\Entity(repositoryClass="Ood\BlogBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\EntityListeners({"Ood\BlogBundle\EventListener\Entity\PostListener"})
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
     * Contains the unique ID of the blogpost represented by a unique character string
     *
     * @var string|null
     * @ORM\Column(
     *     name="unique_id",
     *     type="string",
     *     length=255,
     *     nullable=true,
     *     unique=true,
     *     options={"comment"="Contains the unique ID of the blogpost represented by a unique character string"}
     * )
     */
    protected $uniqueID;

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
     * @var Header
     *
     * @ORM\OneToOne (
     *     targetEntity="Ood\BlogBundle\Entity\Header",
     *     cascade={"persist", "remove"}
     * )
     *
     * @ORM\JoinColumn(
     *     name="header",
     *     referencedColumnName="id_header"
     * )
     *
     * @Assert\Valid()
     */
    protected $header = null;

    /**
     * @var Body
     *
     * @ORM\OneToOne (
     *     targetEntity="Ood\BlogBundle\Entity\Body",
     *     cascade={"persist", "remove"}
     * )
     *
     * @ORM\JoinColumn(
     *     name="body",
     *     referencedColumnName="id_body"
     * )
     *
     * @Assert\Valid()
     */
    protected $body = null;

    /**
     * @var null|\Ood\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(
     *     targetEntity="Ood\UserBundle\Entity\User",
     *     cascade={"persist"}
     * )
     *
     * @ORM\JoinColumn(
     *     name="blogger",
     *     referencedColumnName="id_user"
     * )
     *
     * @Assert\Valid()
     */
    protected $blogger = null;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(
     *     targetEntity="Ood\BlogBundle\Entity\Category",
     *     cascade={"persist"}
     * )
     *
     * @ORM\JoinColumn(
     *     name="category",
     *     referencedColumnName="id_category"
     * )
     *
     * @Assert\NotNull(
     *     message="post.category.not_null"
     * )
     */
    protected $category = null;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Ood\PictureBundle\Entity\Image",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     *
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
     *
     * @Assert\Valid()
     */
    protected $images;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Ood\PictureBundle\Entity\Video",
     *      cascade={"persist", "remove"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(name="posts_videos",
     *      joinColumns={
     *              @ORM\JoinColumn(name="post_id",
     *              referencedColumnName="id_post")
     *      },
     *      inverseJoinColumns={
     *              @ORM\JoinColumn(name="video_id",
     *              referencedColumnName="id_video")
     *      }
     * )
     *
     * @Assert\Valid()
     */
    protected $videos;


    /** *******************************
     *  CONSTRUCT
     */

    public function __construct()
    {
        $dateAt = new \DateTime();
        $this->setCreateAt($dateAt)
             ->setUpdateAt($dateAt);

        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
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
     * @return string|null
     */
    public function getUniqueID(): ?string
    {
        return $this->uniqueID;
    }

    /**
     * @param string|null $uniqueID
     *
     * @return Post
     */
    public function setUniqueID(?string $uniqueID): Post
    {
        $this->uniqueID = $uniqueID;
        return $this;
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
     * @return null|Blogger
     */
    public function getBlogger(): ?Blogger
    {
        return $this->blogger;
    }

    /**
     * @param Blogger $blogger
     *
     * @return Post
     */
    public function setBlogger(Blogger $blogger): Post
    {
        $this->blogger = $blogger;
        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     *
     * @return Post
     */
    public function setCategory(?Category $category): Post
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Body|null
     */
    public function getBody(): ?Body
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
     * @return Header|null
     */
    public function getHeader(): ?Header
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
     * @param Image $image
     *
     * @return Post
     */
    public function addImage(Image $image): Post
    {
        $this->images[] = $image;
        return $this;
    }

    /**
     * @param Image $image
     *
     * @return void
     */
    public function removeImage(Image $image): void
    {
        $this->images->removeElement($image);
    }

    /**
     * @return Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param Video $video
     *
     * @return Post
     */
    public function addVideo(Video $video): Post
    {
        $this->videos[] = $video;
        return $this;
    }

    /**
     * @param Video $video
     *
     * @return void
     */
    public function removeVideo(Video $video): void
    {
        $this->videos->removeElement($video);
    }

    /**
     * @return Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }
}
