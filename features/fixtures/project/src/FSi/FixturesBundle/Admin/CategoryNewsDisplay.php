<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Annotation as Admin;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Display\PropertyAccessDisplay;
use FSi\Bundle\AdminBundle\Display\Property\Formatter;
use FSi\Bundle\AdminBundle\Doctrine\Admin\DependentDisplayElement;
use FSi\FixturesBundle\Entity;

class CategoryNewsDisplay extends DependentDisplayElement
{
    public const ID = 'category_news_display';

    public function getId(): string
    {
        return self::ID;
    }

    public function getParentId(): string
    {
        return 'category';
    }

    public function getClassName(): string
    {
        return Entity\News::class;
    }

    /**
     * @param mixed $object
     * @return Display
     */
    protected function initDisplay($object): Display
    {
        $display = new PropertyAccessDisplay($object);
        $display->add('id', 'Identity')
            ->add('title')
            ->add('date', null, [
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ])
            ->add('visible', 'Visible', [
                new Formatter\Boolean('yes', 'no')
            ])
            ->add('createdAt', null, [
                new Formatter\EmptyValue(),
                new Formatter\DateTime('Y-m-d H:i:s')
            ])
            ->add('creatorEmail');

        return $display;
    }
}
