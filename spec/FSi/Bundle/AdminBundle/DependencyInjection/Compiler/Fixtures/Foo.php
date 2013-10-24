<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
