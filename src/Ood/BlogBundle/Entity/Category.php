<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category
 *
 * @package Ood\BlogBundle\Entity
 *
 * @ORM\Table(name="blogpost_category")
 * @ORM\Entity(repositoryClass="Ood\BlogBundle\Repository\CategoryRepository")
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
     * @var string
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     unique=true,
     *     length=40,
     *     options={"comment"="Contains the name of the category"}
     * )
     *
     * @Assert\Length(
     *     max=40,
     *     maxMessage="category.name.max_length"
     * )
     */
    protected $name;


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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Category
     */
    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }
}
