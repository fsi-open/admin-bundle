<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Display\ObjectDisplay;
use FSi\Bundle\AdminBundle\Display\Property;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Display\DisplayElement;
use FSi\Bundle\AdminBundle\Annotation as Admin;

/**
 * @Admin\Element
 */
class DisplayNews extends DisplayElement
{
    const ID = 'news-display';

    /**
     * @return string
     */
    public function getId()
    {
        return self::ID;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin.news.display';
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\News';
    }

    /**
     * @param mixed $object
     * @return Display
     */
    protected function initDisplay($object)
    {
        $display = new ObjectDisplay($object);
        $display->add(new Property('id', 'Identity'))
            ->add(new Property('title'))
            ->add(new Property('date'))
            ->add(new Property('visible'))
            ->add(new Property('createdAt'))
            ->add(new Property('creatorEmail'));

        return $display;
    }
}
