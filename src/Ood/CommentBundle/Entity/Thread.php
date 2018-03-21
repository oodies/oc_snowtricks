<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     *  CONSTRUCT
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->commentCounter(0);

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
     * @param int $idThread
     *
     * @return Thread
     */
    public function setIdThread(int $idThread): Thread
    {
        $this->idThread = $idThread;
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
     * @return null|int
     */
    public function getNumberOfComment(): ?int
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

    /** *******************************
     *  BEHAVIOR OF THE OBJECT MODEL
     */


    /**
     * Update the number of approved comments counter
     *
     * @param int|null $value (null = +1 or only accept -1)
     *
     * @throws \Exception
     */
    public function commentCounter(int $value = null)
    {
        if ($value === null) {
            $value = 1;
        }

        if ($value < -1) {
            throw new \Exception("Value must be equal to -1 at positive integer");
        }

        $this->numberOfComment = (int)$this->getNumberOfComment() + $value;

        if ($this->numberOfComment < 0) {
            $this->numberOfComment = 0;
        }

        $this->setUpdateAt(new \DateTime());
    }
}
