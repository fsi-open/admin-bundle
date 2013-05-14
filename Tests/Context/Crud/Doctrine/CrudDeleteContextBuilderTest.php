<?php

namespace FSi\Bundle\AdminBundle\Tests\Cotnext\Crud\Doctrine;

use FSi\Bundle\AdminBundle\Context\Crud\Doctrine\CrudDeleteContextBuilder;
use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use FSi\Bundle\AdminBundle\Tests\Cotnext\Crud\AbstractCrudDeleteContextBuilderTest;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CrudDeleteContextBuilderTest extends AbstractCrudDeleteContextBuilderTest
{
    /**
     * @return ElementInterface
     */
    public function getElement()
    {
        $element = $this->getMock('FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface');
        $element->expects($this->any())
            ->method('getOption')
            ->with('allow_delete')
            ->will($this->returnValue(true));

        return $element;
    }

    /**
     * @param ElementInterface $element
     * @return CrudDeleteContextBuilder|mixed
     */
    public function getContextBuilder(ElementInterface $element)
    {
        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        return new CrudDeleteContextBuilder($element, $formFactory, array('fake_id_1', 'fake_id_2'));
    }

    /**
     * @param ElementInterface $element
     * @param FormFactoryInterface $factory
     * @param array $indexes
     * @return CrudDeleteContextBuilder
     */
    public function getContextBuilderForFactoryAndIndexes(ElementInterface $element, FormFactoryInterface $factory, $indexes = array())
    {
        return new CrudDeleteContextBuilder($element, $factory, $indexes);
    }
}