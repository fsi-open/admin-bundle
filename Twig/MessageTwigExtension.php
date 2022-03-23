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
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MessageTwigExtension extends AbstractExtension
{
    private FlashMessages $flashMessages;

    public function __construct(FlashMessages $flashMessages)
    {
        $this->flashMessages = $flashMessages;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('fsi_admin_messages', [$this, 'getMessages']),
        ];
    }

    /**
     * @return array<string,array{text:string,domain:string,params:array<string,mixed>}>
     */
    public function getMessages(): array
    {
        return $this->flashMessages->all();
    }

    public function getName(): string
    {
        return 'fsi_admin_messages';
    }
}
