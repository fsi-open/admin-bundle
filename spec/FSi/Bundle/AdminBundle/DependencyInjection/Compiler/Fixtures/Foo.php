<?php

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler\Fixtures;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;

class Foo extends CRUDElement
{
    public function getClassName()
    {
        return 'FSiDemoBundle:Foo';
    }

    public function getId()
    {
        return 'foo';
    }

    public function getName()
    {
        return 'foo';
    }
}
