<?php

namespace spec\FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property;
use FSi\Bundle\AdminBundle\Display\PropertyView;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

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

        $this->add(new Property('firstName', 'First Name'));
        $this->shouldThrow(new NoSuchPropertyException('Neither the property "firstName" nor one of the methods "getFirstName()", "isFirstName()", "hasFirstName()", "__get()", "__call()" exist and have public access in class "stdClass".'))
            ->during('createView', array());
    }

    function it_create_display_view_for_object()
    {
        $object = new \stdClass();
        $object->first_name = 'Norbert';
        $object->roles = array('ROLE_ADMIN', 'ROLE_USER');

        $this->beConstructedWith($object);

        $this->add(new Property('first_name', 'First Name'));
        $this->add(new Property('roles'));

        $this->createView()->shouldHavePropertyView(new PropertyView('Norbert', 'First Name'));
        $this->createView()->shouldHavePropertyView(new PropertyView(array('ROLE_ADMIN', 'ROLE_USER'), null));
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
