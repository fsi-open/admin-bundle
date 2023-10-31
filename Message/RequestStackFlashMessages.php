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

use function method_exists;

class RequestStackFlashMessages extends FlashMessages
{
    private RequestStack $requestStack;
    private ?FlashBagInterface $flashBag;

    public function __construct(RequestStack $requestStack, string $prefix)
    {
        parent::__construct($prefix);
        $this->requestStack = $requestStack;
        $this->flashBag = null;
    }

    protected function getFlashBag(): FlashBagInterface
    {
        if (false === $this->flashBag instanceof FlashBagInterface) {
            $session = $this->requestStack->getSession();
            $this->flashBag = true === method_exists($session, 'getFlashBag')
                ? $session->getFlashBag()
                : new FlashBag()
            ;
        }

        return $this->flashBag;
    }
}
