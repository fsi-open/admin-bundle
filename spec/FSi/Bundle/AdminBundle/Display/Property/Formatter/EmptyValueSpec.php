<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Display\Property\Formatter;

use PhpSpec\ObjectBehavior;

class EmptyValueSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('-');
    }

    public function it_format_empty_values(): void
    {
        $this->format(0)->shouldReturn('-');
        $this->format(null)->shouldReturn('-');
        $this->format([])->shouldReturn('-');
    }

    public function it_ignore_not_empty_value(): void
    {
        $datetime = new \DateTime();
        $this->format($datetime)->shouldReturn($datetime);
    }
}
