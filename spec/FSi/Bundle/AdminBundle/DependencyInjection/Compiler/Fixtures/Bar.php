<?php

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler\Fixtures;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;

class Bar extends CRUDElement
{
    public function getClassName()
    {
        return 'FSiDemoBundle:Bar';
    }

    public function getId()
    {
        return 'bar';
    }

    public function getName()
    {
        return 'bar';
    }
}
