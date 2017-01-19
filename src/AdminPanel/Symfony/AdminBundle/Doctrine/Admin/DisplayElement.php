<?php


namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\Display\GenericDisplayElement;

abstract class DisplayElement extends GenericDisplayElement implements Element
{
    use DataIndexerElementImpl;
}
