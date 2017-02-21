<?php

/**
* (c) FSi sp. z o.o. <info@fsi.pl>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace spec\FSi\Bundle\AdminBundle\Twig;

use FSi\Bundle\AdminBundle\Message\FlashMessages;
use PhpSpec\ObjectBehavior;

class MessageTwigExtensionSpec extends ObjectBehavior
{
    public function let(FlashMessages $flashMessages)
    {
        $this->beConstructedWith($flashMessages);
    }

    public function it_has_name()
    {
        $this->getName()->shouldReturn('fsi_admin_messages');
    }

    public function it_return_all_messages(FlashMessages $flashMessages)
    {
        $flashMessages->all()->willReturn([
            'success' => [
                ['text' => 'aaa', 'domain' => 'bbb'],
                ['text' => 'ccc', 'domain' => 'ddd'],
            ],
            'error' => [
                ['text' => 'eee', 'domain' => 'fff'],
            ],
        ]);

        $this->getMessages()->shouldReturn([
            'success' => [
                ['text' => 'aaa', 'domain' => 'bbb'],
                ['text' => 'ccc', 'domain' => 'ddd'],
            ],
            'error' => [
                ['text' => 'eee', 'domain' => 'fff'],
            ],
        ]);
    }
}
