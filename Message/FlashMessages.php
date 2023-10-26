<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Message;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use function method_exists;

class FlashMessages
{
    private const DOMAIN = 'FSiAdminBundle';

    private ?FlashBagInterface $flashBag = null;
    private string $prefix;
    private SessionInterface $session;
    private RequestStack $requestStack;

    public function __construct(SessionInterface $session, RequestStack $requestStack, string $prefix)
    {
        $this->prefix = $prefix;
        $this->session = $session;
        $this->requestStack = $requestStack;
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

    private function getFlashBag(): FlashBagInterface
    {
        if (false === $this->flashBag instanceof FlashBagInterface) {
            if (true === method_exists($this->requestStack, 'getSession')) {
                $session = $this->requestStack->getSession();
            } else {
                $session = $this->session;
            }

            $this->flashBag = true === method_exists($session, 'getFlashBag')
                ? $session->getFlashBag()
                : new FlashBag()
            ;
        }

        return $this->flashBag;
    }
}
