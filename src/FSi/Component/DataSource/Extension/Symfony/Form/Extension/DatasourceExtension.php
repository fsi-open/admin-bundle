<?php

namespace FSi\Component\DataSource\Extension\Symfony\Form\Extension;


use FSi\Component\DataSource\Extension\Symfony\Form\Type\BetweenType;
use Symfony\Component\Form\AbstractExtension;

class DatasourceExtension extends AbstractExtension
{
    protected function loadTypes()
    {
        return array(new BetweenType());
    }
}
