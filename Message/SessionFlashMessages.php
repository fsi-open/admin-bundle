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

use function method_exists;

class SessionFlashMessages extends FlashMessages
{
    private SessionInterface $session;
    private ?FlashBagInterface $flashBag;

    public function __construct(SessionInterface $session, string $prefix)
    {
        parent::__construct($prefix);
        $this->session = $session;
        $this->flashBag = null;
    }

    protected function getFlashBag(): FlashBagInterface
    {
        if (false === $this->flashBag instanceof FlashBagInterface) {
            /** @var FlashBagInterface $flashBag */
            $flashBag = true === method_exists($this->session, 'getFlashBag')
                ? $this->session->getFlashBag()
                : new FlashBag()
            ;
            $this->flashBag = $flashBag;
        }

        return $this->flashBag;
    }
}
