<?php

namespace FSi\Bundle\AdminBundle\Tests\Cotnext\Crud\Doctrine;

use FSi\Bundle\AdminBundle\Context\Crud\Doctrine\CrudEditContextBuilder;
use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use FSi\Bundle\AdminBundle\Tests\Cotnext\Crud\AbstractCrudEditContextBuilderTest;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CrudEditContextBuilderTest extends AbstractCrudEditContextBuilderTest
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
     * @return CrudEditContextBuilder|mixed
     */
    public function getContextBuilder(ElementInterface $element)
    {
        return new CrudEditContextBuilder($element, 'fake_id');
    }

    /**
     * @param ElementInterface $element
     * @param $id
     * @return CrudEditContextBuilder
     */
    public function getContextBuilderForId(ElementInterface $element, $id)
    {
        return new CrudEditContextBuilder($element, $id);
    }
}