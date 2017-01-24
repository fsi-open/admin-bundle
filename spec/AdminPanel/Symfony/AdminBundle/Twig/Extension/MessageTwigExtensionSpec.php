<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\AdminPanel\Symfony\AdminBundle\Twig\Extension;

use PhpSpec\ObjectBehavior;

class MessageTwigExtensionSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function let($flashMessages)
    {
        $this->beConstructedWith($flashMessages);
    }

    public function it_has_name()
    {
        $this->getName()->shouldReturn('fsi_admin_messages');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Message\FlashMessages $flashMessages
     */
    public function it_return_all_messages($flashMessages)
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
