<?php

namespace FSi\Bundle\AdminBundle\Tests\Cotnext\Crud\Doctrine;

use FSi\Bundle\AdminBundle\Context\Crud\Doctrine\CrudListContextBuilder;
use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use FSi\Bundle\AdminBundle\Tests\Cotnext\Crud\AbstractCrudListContextBuilderTest;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CrudListContextBuilderTest extends AbstractCrudListContextBuilderTest
{
    /**
     * @return ElementInterface
     */
    public function getElement()
    {
        $element = $this->getMock('FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface');

        return $element;
    }

    /**
     * @param ElementInterface $element
     * @return CrudListContextBuilder|mixed
     */
    public function getContextBuilder(ElementInterface $element)
    {
        return new CrudListContextBuilder($element);
    }
}