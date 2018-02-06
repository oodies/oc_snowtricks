<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ood\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment
 *
 * @package Ood\CommentBundle\Entity
 *
 * @ORM\Table(name="comment_comment")
 * @ORM\Entity(repositoryClass="Ood\CommentBundle\Repository\CommentRepository")
 */
class Comment
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * Contains the ID of the comment
     *
     * @var int
     *
     * @ORM\Column(
     *     name="id_comment",
     *     type="integer",
     *     unique=true,
     *     length=11,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the comment"
     *      }
     * )
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $idComment;


    /**
     * Contains the date of creation of the comment
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="create_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Contains the date of creation of the comment"}
     * )
     *
     * @Assert\DateTime(
     *     message="comment.createAt.not_validate"
     * )
     */
    protected $createAt;

    /**
     * Contains the update date of the comment
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="update_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Contains the update date of the comment"}
     * )
     *
     * @Assert\DateTime(
     *     message="comment.updateAt.not_validate"
     * )
     */
    protected $updateAt;

    /**
     * Contains the body of the comment
     *
     * @var string
     *
     * @ORM\Column(
     *     name="body",
     *     type="text",
     *     nullable=true,
     *     options={"comment"="Contains the body of the comment"}
     * )
     */
    protected $body;

    /**
     * Says whether or not the how is active
     *
     * @var bool
     *
     * @ORM\Column(
     *     name="enabled",
     *     type="boolean",
     *     nullable=false,
     *     options={"comment"="Says whether or not the how is active",
     *              "default"="0"
     *     }
     * )
     */
    protected $enabled;

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
     * @ORM\JoinColumn(name="author", referencedColumnName="id_user")
     */
    protected $author;

    /**
     * @var Thread
     *
     * @ORM\ManyToOne(
     *     targetEntity="Ood\CommentBundle\Entity\Thread",
     *     cascade={"persist"}
     * )
     *
     * @ORM\JoinColumn(name="thread", referencedColumnName="id_thread")
     */
    protected $thread;

    /** *******************************
     * CONSTRUCT
     */

    public function __construct()
    {
        $dateAt = new \DateTime();
        $this->setCreateAt($dateAt)
             ->setUpdateAt($dateAt)
             ->setEnabled(true);
    }

    /** *******************************
     * GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getIdComment(): int
    {
        return $this->idComment;
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
     * @return Comment
     */
    public function setCreateAt(\DateTime $createAt): Comment
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
     * @return Comment
     */
    public function setUpdateAt(\DateTime $updateAt): Comment
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return Comment
     */
    public function setBody(string $body): Comment
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return Comment
     */
    public function setEnabled(bool $enabled): Comment
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param null|User $author
     *
     * @return Comment
     */
    public function setAuthor(?User $author): Comment
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return Thread|null
     */
    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    /**
     * @param null|Thread $thread
     *
     * @return Comment
     */
    public function setThread(?Thread $thread): Comment
    {
        $this->thread = $thread;
        return $this;
    }
}
