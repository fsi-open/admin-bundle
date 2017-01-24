<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Tests\Fixtures\Form\Extension\TestCore;

use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\PropertyAccess\PropertyAccess;
use FSi\Component\DataSource\Tests\Fixtures\Form\Extension\TestCore\Type;

class TestCoreExtension extends AbstractExtension
{
    protected function loadTypes()
    {
        return [
            new Type\FormType(PropertyAccess::createPropertyAccessor()),
        ];
    }
}
