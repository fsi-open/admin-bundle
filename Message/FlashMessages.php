<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Message;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FlashMessages
{
    private ?FlashBagInterface $flashBag = null;

    private string $prefix;

    private SessionInterface $session;

    public function __construct(SessionInterface $session, string $prefix)
    {
        $this->prefix = $prefix;
        $this->session = $session;
    }

    /**
     * @param string $message
     * @param array<string,mixed> $params
     * @param string $domain
     */
    public function success(string $message, array $params = [], string $domain = 'FSiAdminBundle'): void
    {
        $this->add('success', $message, $params, $domain);
    }

    /**
     * @param string $message
     * @param array<string,mixed> $params
     * @param string $domain
     */
    public function error(string $message, array $params = [], string $domain = 'FSiAdminBundle'): void
    {
        $this->add('error', $message, $params, $domain);
    }

    /**
     * @param string $message
     * @param array<string,mixed> $params
     * @param string $domain
     */
    public function warning(string $message, array $params = [], string $domain = 'FSiAdminBundle'): void
    {
        $this->add('warning', $message, $params, $domain);
    }

    /**
     * @param string $message
     * @param array<string,mixed> $params
     * @param string $domain
     */
    public function info(string $message, array $params = [], string $domain = 'FSiAdminBundle'): void
    {
        $this->add('info', $message, $params, $domain);
    }

    /**
     * @return array<string,array{text:string,domain:string,params:array<string,mixed>}>
     */
    public function all(): array
    {
        return $this->getFlashBag()->get($this->prefix);
    }

    /**
     * @param string $type
     * @param string $message
     * @param array<string,mixed> $params
     * @param string $domain
     */
    private function add(string $type, string $message, array $params, string $domain): void
    {
        $flashBag = $this->getFlashBag();
        if (true === $flashBag->has($this->prefix)) {
            $messages = $flashBag->get($this->prefix);
        } else {
            $messages = [];
        }

        $messages[$type][] = ['text' => $message, 'domain' => $domain, 'params' => $params];

        $flashBag->set($this->prefix, $messages);
    }

    private function getFlashBag(): FlashBagInterface
    {
        if (true === $this->flashBag instanceof FlashBagInterface) {
            return $this->flashBag;
        }

        $this->flashBag = true === method_exists($this->session, 'getFlashBag')
            ? $this->session->getFlashBag()
            : new FlashBag();

        return $this->flashBag;
    }
}
