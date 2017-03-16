<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminBundle\Display\Property\Formatter;
use FSi\Bundle\AdminBundle\Display\SimpleDisplay;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Doctrine\Admin\DisplayElement;
use FSi\FixturesBundle\Entity\News as NewsEntity;

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
     * @param NewsEntity $object
     * @return Display
     */
    protected function initDisplay($object)
    {
        $display = new SimpleDisplay();
        $display->add($object->getId(), 'Identity')
            ->add($object->getTitle(), 'Title')
            ->add($object->getDate(), 'Date', [
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ])
            ->add($object->isVisible(), 'Visible', [
                new Formatter\Boolean("yes", "no")
            ])
            ->add($object->getCategories(), 'Categories')
            ->add($object->getCreatedAt(), 'Created at', [
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ])
            ->add($object->getCreatorEmail(), 'Creator email')
        ;

        return $display;
    }
}
