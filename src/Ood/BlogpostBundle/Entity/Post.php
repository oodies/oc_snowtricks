<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\BlogpostBundle\Entity;

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

    /** *******************************
     *  CONSTRUCT
     */

    public function __construct()
    {
        $dateAt = new \DateTime();
        $this->setCreateAt($dateAt)
             ->setUpdateAt($dateAt);
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
}
