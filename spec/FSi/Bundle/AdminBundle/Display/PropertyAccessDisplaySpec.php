<?php

namespace spec\FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property;
use PhpSpec\ObjectBehavior;

class PropertyAccessDisplaySpec extends ObjectBehavior
{
    function it_throws_exception_when_value_is_not_an_object_or_array()
    {
        $object = 'wrong_data';
        $this->shouldThrow('\InvalidArgumentException')->during('__construct', [$object]);
    }

    function it_throws_exception_when_property_path_is_invalid()
    {
        $object = new \stdClass();
        $object->firstName = 'Norbert';

        $this->beConstructedWith($object);

        $this->shouldThrow('Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException')
            ->during('add', ['first_name', 'First Name']);
    }

    function it_creates_data_for_object()
    {
        $object = new \stdClass();
        $object->firstName = 'Norbert';
        $object->roles = ['ROLE_ADMIN', 'ROLE_USER'];

        $this->beConstructedWith($object);

        $this->add('firstName', 'First Name');
        $this->add('roles', 'Roles');

        $this->getData()->shouldHaveProperty('Norbert', 'First Name');
    }

    function it_creates_display_view_with_decorated_values()
    {
        $now = new \DateTime();
        $object = new \stdClass();
        $object->date = $now;
        $this->beConstructedWith($object);
        $this->add('date', 'Date', [new Property\Formatter\DateTime('Y-m-d')]);

        $this->getData()->shouldHaveProperty($now->format('Y-m-d'), 'Date');
    }

    public function getMatchers()
    {
        return [
            'haveProperty' => function($subject, $value, $label) {
                /* @var $property Property */
                foreach ($subject as $property) {
                    if ($property->getLabel() === $label && $property->getValue() === $value) {
                        return true;
                    }
                }
            },
        ];
    }
}
