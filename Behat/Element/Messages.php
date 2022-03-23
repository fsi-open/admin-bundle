<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Element;

use Exception;
use FriendsOfBehat\PageObjectExtension\Element\Element;

class Messages extends Element
{
    public function getMessageText(string $type): string
    {
        $alerts = $this->getElement('messages')->findAll('css', sprintf('.alert-%s', $type));
        if (count($alerts) < 1) {
            throw new Exception(sprintf("Unable to find any alert with type '%s'", $type));
        }

        return implode("\n", array_map(static function ($alert) {
            return $alert->getText();
        }, $alerts));
    }

    protected function getDefinedElements(): array
    {
        return [
            'messages' => '#messages',
        ];
    }
}
