<?php

namespace FSi\Bundle\AdminBundle\Tests\Cotnext\Crud;

use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use FSi\Bundle\AdminBundle\Tests\Cotnext\AbstractContextBuilderTest;
use FSi\Bundle\AdminBundle\Tests\Fixtures\News;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractCrudEditContextBuilderTest extends AbstractContextBuilderTest
{
    /**
     * @param ElementInterface $element
     * @param $id
     * @return \FSi\Bundle\AdminBundle\Context\Crud\AbstractCrudEditContextBuilder
     */
    abstract public function getContextBuilderForId(ElementInterface $element, $id);

    public function testBuildContext()
    {
        $element = $this->getElement();
        $self = $this;

        $element->expects($this->any())
            ->method('hasOption')
            ->with('template_crud_edit')
            ->will($this->returnValue(false));
        $element->expects($this->any())
            ->method('hasEditForm')
            ->will($this->returnValue(true));
        $element->expects($this->any())
            ->method('getEditForm')
            ->will($this->returnValue($this->getMockBuilder('Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock()
            ));
        $element->expects($this->any())
            ->method('getDataIndexer')
            ->will($this->returnCallback(function() use ($self) {
                $mock = $self->getMock('FSi\Component\DataIndexer\DataIndexerInterface');

                $mock->expects($this->any())
                    ->method('getData')
                    ->with('id')
                    ->will($this->returnValue(new News()));

                return $mock;
            }));

        $builder = $this->getContextBuilderForId($element, 'id');
        $context = $builder->buildContext();

        $this->assertNull($context->getTemplateName());
        $this->assertInstanceOf('FSi\Bundle\AdminBundle\Tests\Fixtures\News', $context->getEntity());
        $this->assertInstanceOf('Symfony\Component\Form\FormInterface', $context->getForm());
        $this->assertSame('id', $context->getEntityId());
    }

    /**
     * @expectedException \FSi\Bundle\AdminBundle\Exception\MissingFormException
     */
    public function testBuildContextWithoutForm()
    {
        $element = $this->getElement();
        $self = $this;

        $element->expects($this->any())
            ->method('hasOption')
            ->with('template_crud_edit')
            ->will($this->returnValue(false));

        $element->expects($this->any())
            ->method('hasCreateForm')
            ->will($this->returnValue(false));

        $element->expects($this->any())
            ->method('getDataIndexer')
            ->will($this->returnCallback(function() use ($self) {
                $mock = $self->getMock('FSi\Component\DataIndexer\DataIndexerInterface');

                $mock->expects($this->any())
                    ->method('getData')
                    ->with('id')
                    ->will($this->returnValue(new News()));

                return $mock;
            }));

        $builder = $this->getContextBuilderForId($element, 'id');
        $context = $builder->buildContext();
    }

    /**
     * @expectedException \FSi\Bundle\AdminBundle\Exception\InvalidEntityIdException
     */
    public function testBuildContextWithoutEntityForm()
    {
        $element = $this->getElement();
        $self = $this;

        $element->expects($this->any())
            ->method('hasOption')
            ->with('template_crud_edit')
            ->will($this->returnValue(false));

        $element->expects($this->any())
            ->method('hasCreateForm')
            ->will($this->returnValue(false));

        $element->expects($this->any())
            ->method('getDataIndexer')
            ->will($this->returnCallback(function() use ($self) {
                $mock = $self->getMock('FSi\Component\DataIndexer\DataIndexerInterface');

                $mock->expects($this->any())
                    ->method('getData')
                    ->with('id')
                    ->will($this->returnValue(null));

                return $mock;
            }));

        $builder = $this->getContextBuilderForId($element, 'id');
        $context = $builder->buildContext();
    }
}