<?php

namespace FSi\Bundle\AdminBundle\Tests\Cotnext\Crud;

use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use FSi\Bundle\AdminBundle\Tests\Cotnext\AbstractContextBuilderTest;
use FSi\Bundle\AdminBundle\Tests\Fixtures\News;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractCrudDeleteContextBuilderTest extends AbstractContextBuilderTest
{
    /**
     * @param ElementInterface $element
     * @param FormFactoryInterface $factory
     * @param array $indexes
     * @return \FSi\Bundle\AdminBundle\Context\Crud\AbstractCrudDeleteContextBuilder
     */
    abstract public function getContextBuilderForFactoryAndIndexes(ElementInterface $element, FormFactoryInterface $factory, $indexes = array());

    public function testBuildContext()
    {
        $element = $this->getElement();
        $self = $this;

        $element->expects($this->any())
            ->method('getDataIndexer')
            ->will($this->returnCallback(function() use ($self) {
                $mock = $self->getMock('FSi\Component\DataIndexer\DataIndexerInterface');

                $mock->expects($this->any())
                    ->method('getData')
                    ->will($this->returnValue(new News()));

                return $mock;
            }));

        $factory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $factory->expects($this->any())
                ->method('create')
                ->will($this->returnValue($this->getMockBuilder('Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock()
            ));

        $builder = $this->getContextBuilderForFactoryAndIndexes($element, $factory, array('index_1', 'index_2'));
        $context = $builder->buildContext();

        $this->assertNull($context->getTemplateName());
        $entites = $context->getEntities();
        $this->assertInstanceOf('Symfony\Component\Form\FormInterface', $context->getForm());
        $this->assertInstanceOf('FSi\Bundle\AdminBundle\Tests\Fixtures\News', $entites[0]);
        $this->assertInstanceOf('FSi\Bundle\AdminBundle\Tests\Fixtures\News', $entites[1]);
    }

    /**
     * @expectedException \FSi\Bundle\AdminBundle\Exception\InvalidArgumentException
     */
    public function testBuildContextWhereOneEntityCantBeFound()
    {
        $element = $this->getElement();
        $self = $this;

        $element->expects($this->any())
            ->method('getDataIndexer')
            ->will($this->returnCallback(function() use ($self) {
                $mock = $self->getMock('FSi\Component\DataIndexer\DataIndexerInterface');

                $mock->expects($this->any())
                    ->method('getData')
                    ->will($this->returnCallback(function($id) {
                        if ($id == 'index_2') {
                            return null;
                        }

                        return new News();
                    }));

                return $mock;
            }));

        $factory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $builder = $this->getContextBuilderForFactoryAndIndexes($element, $factory, array('index_1', 'index_2'));
        $context = $builder->buildContext();
    }
}