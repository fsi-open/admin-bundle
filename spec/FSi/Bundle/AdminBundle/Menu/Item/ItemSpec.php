<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Menu\Item;

use PhpSpec\ObjectBehavior;

class ItemSpec extends ObjectBehavior
{
    public function it_has_default_options(): void
    {
        $this->getOptions()->shouldReturn(['attr' => ['id' => null, 'class' => null]]);
    }
}
