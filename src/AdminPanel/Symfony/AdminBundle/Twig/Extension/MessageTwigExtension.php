<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Twig\Extension;

use AdminPanel\Symfony\AdminBundle\Message\FlashMessages;
use Twig_Extension;
use Twig_SimpleFunction;

class MessageTwigExtension extends Twig_Extension
{
    /**
     * @var FlashMessages
     */
    private $flashMessages;

    /**
     * @param FlashMessages $flashMessages
     */
    public function __construct(FlashMessages $flashMessages)
    {
        $this->flashMessages = $flashMessages;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('fsi_admin_messages', [$this, 'getMessages']),
        ];
    }

    public function getMessages()
    {
        return $this->flashMessages->all();
    }

    public function getName()
    {
        return 'fsi_admin_messages';
    }
}
