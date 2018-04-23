<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace Ood\BlogBundle\EventListener\Entity;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Ood\BlogBundle\Entity\Category;
use Ood\BlogBundle\Services\Sluggable;

/**
 * Class CategoryListener
 *
 * @package Ood\BlogBundle\EventListener\Entity
 */
class CategoryListener
{
    /** *******************************
     *  PROPERTIES
     */

    /** @var Sluggable $sluggable */
    protected $sugglable;


    /** *******************************
     *  METHODS
     */

    /**
     * HeaderListener constructor.
     *
     * @param Sluggable $sluggable
     */
    public function __construct(Sluggable $sluggable)
    {
        $this->sugglable = $sluggable;
    }

    /**
     * @param Category $category
     *
     * @ORM\PrePersist()
     */
    public function onPrePersist(Category $category)
    {
        $category->setSlug($this->sugglable->slug($category->getName()));
    }

    /**
     * @param PreUpdateEventArgs $eventArgs
     * @param Category           $category
     *
     * @ORM\PreUpdate()
     */
    public function onPreUpdate(Category $category, PreUpdateEventArgs $eventArgs)
    {
        if ($eventArgs->hasChangedField('name')) {
            $category->setSlug($this->sugglable->slug($category->getName()));
        }
    }
}
