<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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

    public function __construct(FlashMessages $flashMessages)
    {
        $this->flashMessages = $flashMessages;
    }

    public function getFunctions(): array
    {
        return [
            new Twig_SimpleFunction('fsi_admin_messages', [$this, 'getMessages']),
        ];
    }

    public function getMessages(): array
    {
        return $this->flashMessages->all();
    }

    public function getName(): string
    {
        return 'fsi_admin_messages';
    }
}
