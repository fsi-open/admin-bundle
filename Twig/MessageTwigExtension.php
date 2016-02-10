<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Twig;

use FSi\Bundle\AdminBundle\Message\FlashMessages;
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
