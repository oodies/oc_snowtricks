<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogBundle\EventListener\Entity;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Ood\BlogBundle\Entity\Header;
use Ood\BlogBundle\Services\Sluggable;

/**
 * Class HeaderListener
 *
 * @package Ood\BlogBundle\EventListener\Entity
 */
class HeaderListener
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
     * @param Header $header
     *
     * @ORM\PrePersist()
     */
    public function onPrePersist(Header $header)
    {
        $header->setSlug($this->sugglable->slug($header->getTitle()));
    }

    /**
     * @param PreUpdateEventArgs $eventArgs
     * @param Header             $header
     *
     * @ORM\PreUpdate()
     */
    public function onPreUpdate(Header $header, PreUpdateEventArgs $eventArgs)
    {
        if ($eventArgs->hasChangedField('title')) {
            $header->setSlug($this->sugglable->slug($header->getTitle()));
        }
    }
}
