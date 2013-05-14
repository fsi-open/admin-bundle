<?php

namespace FSi\Bundle\AdminBundle\Tests\Cotnext\Crud;

use FSi\Bundle\AdminBundle\Tests\Cotnext\AbstractContextBuilderTest;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractCrudCreateContextBuilderTest extends AbstractContextBuilderTest
{
    public function testBuildContext()
    {
        $element = $this->getElement();

        $element->expects($this->any())
            ->method('hasOption')
            ->with('template_crud_create')
            ->will($this->returnValue(false));

        $element->expects($this->any())
            ->method('hasCreateForm')
            ->will($this->returnValue(true));

        $element->expects($this->any())
            ->method('getCreateForm')
            ->will($this->returnValue($this->getMockBuilder('Symfony\Component\Form\Form')
                ->disableOriginalConstructor()
                ->getMock()
            ));

        $builder = $this->getContextBuilder($element);
        $context = $builder->buildContext();
        $this->assertNull($context->getTemplateName());
        $this->assertInstanceOf('Symfony\Component\Form\Form', $context->getForm());
    }

    /**
     * @expectedException \FSi\Bundle\AdminBundle\Exception\MissingFormException
     */
    public function testBuildContextWithoutForm()
    {
        $element = $this->getElement();

        $element->expects($this->any())
            ->method('hasOption')
            ->with('template_crud_create')
            ->will($this->returnValue(false));

        $element->expects($this->any())
            ->method('hasCreateForm')
            ->will($this->returnValue(false));

        $builder = $this->getContextBuilder($element);
        $context = $builder->buildContext();
    }
}