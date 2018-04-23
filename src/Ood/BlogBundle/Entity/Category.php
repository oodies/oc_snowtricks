<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\BlogBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category
 *
 * @package Ood\BlogBundle\Entity
 *
 * @ORM\Table(name="blog_category",
 *            indexes={@ORM\Index(name="IDX_slug", columns={"slug"} ) } )
 *
 * @ORM\Entity(repositoryClass="Ood\BlogBundle\Repository\CategoryRepository")
 * @ORM\EntityListeners({"Ood\BlogBundle\EventListener\Entity\CategoryListener"})
 *
 * @UniqueEntity("name", message="category.name.unique_entity")
 */
class Category
{
    /**
     * Contains the ID of the category type
     *
     * @var int
     *
     * @ORM\Column(
     *     name="id_category",
     *     type="integer",
     *     unique=true,
     *     length=11,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the category type"
     *     }
     * )
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $idCategory;

    /**
     * @var string|null
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     unique=true,
     *     length=40,
     *     options={"comment"="Contains the name of the category"}
     * )
     *
     * @Assert\NotNull(
     *     message="category.name.not_null"
     * )
     *
     * @Assert\Length(
     *     max=40,
     *     maxMessage="category.name.max_length"
     * )
     */
    protected $name;

    /**
     * Contains the slug matched name category
     *
     * @var string|null
     *
     * @ORM\Column(
     *     name="slug",
     *     type="string",
     *     length=128,
     *     nullable=true,
     *     options={"comment"="Contains the slug matched name category"}
     * )
     */
    protected $slug;

    /**
     * Contains the description of the category
     *
     * @var string|null
     *
     * @ORM\Column(
     *     name="description",
     *     type="text",
     *     nullable=true,
     *     options={
     *      "comment"="Contains the description of the category"}
     * )
     */
    protected $description;


    /**
     * @var Collection
     *
     * @ORM\OneToMany(
     *      targetEntity="Ood\BlogBundle\Entity\Post",
     *      cascade={"persist"},
     *      mappedBy="category"
     * )
     */
    protected $posts;

    /** *******************************
     *  GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getIdCategory(): int
    {
        return $this->idCategory;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return Category
     */
    public function setName(?string $name): Category
    {
        $this->name = $name;
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
     * @return Category
     */
    public function setSlug(?string $slug): Category
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     *
     * @return Category
     */
    public function setDescription(?string $description): Category
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }
}
