<?php

namespace FSi\Bundle\AdminBundle\Tests\Cotnext\Crud\Doctrine;

use FSi\Bundle\AdminBundle\Context\Crud\Doctrine\CrudCreateContextBuilder;
use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use FSi\Bundle\AdminBundle\Tests\Cotnext\Crud\AbstractCrudCreateContextBuilderTest;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CrudCreateContextBuilderTest extends AbstractCrudCreateContextBuilderTest
{
    protected $element;

    /**
     * @return ElementInterface
     */
    public function getElement()
    {
        return $this->getMock('FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface');
    }

    /**
     * @param ElementInterface $element
     * @return CrudCreateContextBuilder|mixed
     */
    public function getContextBuilder(ElementInterface $element)
    {
        return new CrudCreateContextBuilder($element);
    }
}