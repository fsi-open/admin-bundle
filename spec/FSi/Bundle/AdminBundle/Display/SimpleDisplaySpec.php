<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Display;

use PhpSpec\ObjectBehavior;

class SimpleDisplaySpec extends ObjectBehavior
{
    function it_creates_data_for_object()
    {
        $this->add('Piotr', 'First Name');
        $this->add(['ROLE_ADMIN', 'ROLE_USER'], 'Roles');

        $this->getData()->shouldHaveProperty('Piotr', 'First Name');
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
