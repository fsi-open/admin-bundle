<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Fixtures\Form\Extension\TestCore;

use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\PropertyAccess\PropertyAccess;
use FSi\Component\DataSource\Tests\Fixtures\Form\Extension\TestCore\Type;

class TestCoreExtension extends AbstractExtension
{
    protected function loadTypes()
    {
        return array(
            new Type\FormType(PropertyAccess::createPropertyAccessor()),
        );
    }
}
