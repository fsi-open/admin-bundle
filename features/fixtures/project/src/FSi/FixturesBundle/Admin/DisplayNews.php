<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Display\ObjectDisplay;
use FSi\Bundle\AdminBundle\Display\Property\Formatter;
use FSi\Bundle\AdminBundle\Doctrine\Admin\DisplayElement;

/**
 * @Admin\Element
 */
class DisplayNews extends DisplayElement
{
    const ID = 'news_display';

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
        $display->add('id', 'Identity')
            ->add('title')
            ->add('date', null, [
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ])
            ->add('visible', 'Visible', [
                new Formatter\Boolean("yes", "no")
            ])
            ->add('categories')
            ->add('createdAt', null, [
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ])
            ->add('creatorEmail');

        return $display;
    }
}
