<?php

namespace spec\FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectDisplaySpec extends ObjectBehavior
{
    function it_throw_exception_when_value_is_not_an_object()
    {
        $object = array();
        $this->shouldThrow(new \InvalidArgumentException("Argument used to create ObjectDisplay must be an object."))
            ->during('__construct', array($object));
    }

    function it_throw_exception_when_property_path_is_invalid()
    {
        $object = new \stdClass();
        $object->first_name = 'Norbert';

        $this->beConstructedWith($object);

        $this->add('firstName', 'First Name');
        $this->shouldThrow('Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException')
            ->during('createView', array());
    }

    function it_create_display_view_for_object()
    {
        $object = new \stdClass();
        $object->first_name = 'Norbert';
        $object->roles = array('ROLE_ADMIN', 'ROLE_USER');

        $this->beConstructedWith($object);

        $this->add('first_name', 'First Name');
        $this->add('roles');

        $this->createView()->shouldHavePropertyView(new Property\View('Norbert', 'first_name', 'First Name'));
        $this->createView()->shouldHavePropertyView(new Property\View(array('ROLE_ADMIN', 'ROLE_USER'), 'roles', null));
    }

    function it_create_display_view_with_decorated_values()
    {
        $object = new \stdClass();
        $object->date = new \DateTime();
        $this->beConstructedWith($object);
        $this->add('date', null, array(new Property\Formatter\DateTime('Y-m-d')));

        $this->createView()->shouldHavePropertyView(new Property\View($object->date->format('Y-m-d'), 'date', null));
    }

    public function getMatchers()
    {
        return array(
            'havePropertyView' => function($subject, $key) {
                return in_array($key, (array) $subject->getIterator());
            },
        );
    }
}
