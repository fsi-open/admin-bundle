<?php

namespace spec\FSi\Bundle\AdminBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationRegistry;
use FSi\Bundle\AdminBundle\Admin\ElementFactory;
use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminBundle\Finder\AdminClassFinder;
use FSi\Bundle\AdminBundle\spec\fixtures\Admin\SimpleAdminElement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerBuilderSpec extends ObjectBehavior
{
    function let(AdminClassFinder $finder, ElementFactory $elementFactory)
    {
        $this->beConstructedWith($finder, $elementFactory);
        $annotationReflection = new \ReflectionClass('FSi\Bundle\AdminBundle\Annotation\Element');
        AnnotationRegistry::registerFile($annotationReflection->getFileName());
    }

    function it_build_manager_from_class_with_annotation(
        Manager $manager,
        AdminClassFinder $finder,
        ElementFactory $elementFactory,
        SimpleAdminElement $adminElement
    ) {
        $finder->findClasses()->willReturn(array(
            'FSi\Bundle\AdminBundle\spec\fixtures\Admin\SimpleAdminElement'
        ));
        $elementFactory->create('FSi\Bundle\AdminBundle\spec\fixtures\Admin\SimpleAdminElement')->willReturn($adminElement);
        $manager->addElement($adminElement, 'structure')->shouldBeCalled();

        $this->build($manager);
    }
}
