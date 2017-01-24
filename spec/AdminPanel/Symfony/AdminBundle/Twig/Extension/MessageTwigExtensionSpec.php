<?php

declare(strict_types=1);

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
