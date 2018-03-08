<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Body
 *
 * @package Ood\BlogBundle\Entity
 *
 * @ORM\Table(name="blogpost_body")
 * @ORM\Entity(repositoryClass="Ood\BlogBundle\Repository\BodyRepository")
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
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     *
     * @return Body
     */
    public function setContent(?string $content): Body
    {
        $this->content = $content;
        return $this;
    }
}
