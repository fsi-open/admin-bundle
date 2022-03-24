<?php

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property;
use PhpSpec\ObjectBehavior;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

class PropertyAccessDisplaySpec extends ObjectBehavior
{
    public function it_throws_exception_when_value_is_not_an_object_or_array(): void
    {
        $object = 'wrong_data';
        $this->shouldThrow(\InvalidArgumentException::class)->during('__construct', [$object]);
    }

    public function it_throws_exception_when_property_path_is_invalid(): void
    {
        $object = new \stdClass();
        $object->firstName = 'Norbert';

        $this->beConstructedWith($object);

        $this->shouldThrow(NoSuchPropertyException::class)
            ->during('add', ['first_name', 'First Name']);
    }

    public function it_creates_data_for_object(): void
    {
        $object = new \stdClass();
        $object->firstName = 'Norbert';
        $object->roles = ['ROLE_ADMIN', 'ROLE_USER'];

        $this->beConstructedWith($object);

        $this->add('firstName', 'First Name');
        $this->add('roles', 'Roles');

        $this->getData()->shouldHaveProperty('Norbert', 'First Name');
    }

    public function it_creates_display_view_with_decorated_values(): void
    {
        $now = new \DateTime();
        $object = new \stdClass();
        $object->date = $now;
        $this->beConstructedWith($object);
        $this->add('date', 'Date', [new Property\Formatter\DateTime('Y-m-d')]);

        $this->getData()->shouldHaveProperty($now->format('Y-m-d'), 'Date');
    }

    public function getMatchers(): array
    {
        return [
            'haveProperty' => function ($subject, $value, $label) {
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
