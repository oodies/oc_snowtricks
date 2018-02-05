<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ood\BlogpostBundle\Entity\Post;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Thread
 *
 * @package Ood\CommentBundle\Entity
 *
 * @ORM\Table(name="comment_thread")
 * @ORM\Entity(repositoryClass="Ood\CommentBundle\Repository\ThreadRepository")
 */
class Thread
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * Contains the ID of the thread
     *
     * @var int
     *
     * @ORM\Column(
     *     name="id_thread",
     *     type="integer",
     *     unique=true,
     *     length=11,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the thread"
     *      }
     * )
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $idThread;

    /**
     * Contains the date of creation of the thread
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="create_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Contains the date of creation of the thread"}
     * )
     *
     * @Assert\DateTime(
     *     message="thread.createAt.not_validate"
     * )
     */
    protected $createAt;

    /**
     * Contains the update date of the thread
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="update_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Contains the update date of the thread"}
     * )
     *
     * @Assert\DateTime(
     *     message="thread.updateAt.not_validate"
     * )
     */
    protected $updateAt;

    /**
     * Contains the number of comments
     *
     * @var int
     *
     * @ORM\Column(
     *     name="number_of_comment",
     *     type="integer",
     *     nullable=false,
     *     options={"comment"="Contains the number of comments"}
     * )
     */
    protected $numberOfComment;

    /** *******************************
     *  ASSOCIATION MAPPING
     */

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
     *     message="thread.post.not_null"
     *     )
     */
    protected $post;


    /** *******************************
     *  CONSTRUCT
     */
    public function __construct()
    {
        $this->numberOfComment = 0;

        $this->createAt = $this->updateAt = new \DateTime();
    }

    /** *******************************
     * GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getIdThread(): int
    {
        return $this->idThread;
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
     * @return Thread
     */
    public function setCreateAt(\DateTime $createAt): Thread
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
     * @return Thread
     */
    public function setUpdateAt(\DateTime $updateAt): Thread
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfComment(): int
    {
        return $this->numberOfComment;
    }

    /**
     * @param int $numberOfComment
     *
     * @return Thread
     */
    public function setNumberOfComment(int $numberOfComment): Thread
    {
        $this->numberOfComment = $numberOfComment;
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
     * @return Thread
     */
    public function setPost(Post $post): Thread
    {
        $this->post = $post;
        return $this;
    }
}
