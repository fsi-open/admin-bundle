<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Message;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

abstract class FlashMessages
{
    private const DOMAIN = 'FSiAdminBundle';

    private string $prefix;

    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param array<string,mixed> $params
     */
    public function success(string $message, array $params = [], string $domain = self::DOMAIN): void
    {
        $this->add('success', $message, $params, $domain);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function error(string $message, array $params = [], string $domain = self::DOMAIN): void
    {
        $this->add('error', $message, $params, $domain);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function warning(string $message, array $params = [], string $domain = self::DOMAIN): void
    {
        $this->add('warning', $message, $params, $domain);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function info(string $message, array $params = [], string $domain = self::DOMAIN): void
    {
        $this->add('info', $message, $params, $domain);
    }

    /**
     * @return array<string, array{ text: string, domain: string, params: array<string,mixed> }>
     */
    public function all(): array
    {
        return $this->getFlashBag()->get($this->prefix);
    }

    abstract protected function getFlashBag(): FlashBagInterface;

    /**
     * @param array<string,mixed> $params
     */
    private function add(string $type, string $message, array $params, string $domain): void
    {
        $flashBag = $this->getFlashBag();
        $messages = true === $flashBag->has($this->prefix)
            ? $flashBag->get($this->prefix)
            : []
        ;

        $messages[$type][] = ['text' => $message, 'domain' => $domain, 'params' => $params];
        $flashBag->set($this->prefix, $messages);
    }
}
