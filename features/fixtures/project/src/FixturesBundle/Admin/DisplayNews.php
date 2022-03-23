<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Display\Property\Formatter;
use FSi\Bundle\AdminBundle\Display\SimpleDisplay;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Doctrine\Admin\DisplayElement;
use FSi\FixturesBundle\Entity;

class DisplayNews extends DisplayElement
{
    public const ID = 'news_display';

    public function getId(): string
    {
        return self::ID;
    }

    public function getClassName(): string
    {
        return Entity\News::class;
    }

    /**
     * @param Entity\News $object
     * @return Display
     */
    protected function initDisplay($object): Display
    {
        $display = new SimpleDisplay();
        $display->add($object->getId(), 'Identity')
            ->add($object->getTitle(), 'Title')
            ->add($object->getDate(), 'Date', [
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ])
            ->add($object->isVisible(), 'Visible', [
                new Formatter\Boolean('yes', 'no')
            ])
            ->add($object->getCategories(), 'Categories', [
                new Formatter\EmptyValue()
            ])
            ->add($object->getCreatedAt(), 'Created at', [
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ])
            ->add($object->getCreatorEmail(), 'Creator email')
        ;

        return $display;
    }
}
