<?php


namespace AdminPanel\Symfony\AdminBundle\Message;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FlashMessages
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * FlashMessages constructor.
     * @param SessionInterface $session
     * @param $prefix
     */
    public function __construct(SessionInterface $session, $prefix)
    {
        $this->prefix = $prefix;
        $this->session = $session;
    }

    public function success($message, $domain = 'FSiAdminBundle', array $params = [])
    {
        $this->add('success', $message, $domain, $params);
    }

    public function error($message, $domain = 'FSiAdminBundle', array $params = [])
    {
        $this->add('error', $message, $domain, $params);
    }

    public function warning($message, $domain = 'FSiAdminBundle', array $params = [])
    {
        $this->add('warning', $message, $domain, $params);
    }

    public function info($message, $domain = 'FSiAdminBundle', array $params = [])
    {
        $this->add('info', $message, $domain, $params);
    }

    public function all()
    {
        return $this->getFlashBag()->get($this->prefix);
    }

    private function add($type, $message, $domain, array $params = [])
    {
        if ($this->getFlashBag()->has($this->prefix)) {
            $messages = $this->getFlashBag()->get($this->prefix);
        } else {
            $messages = [];
        }

        $messages[$type][] = ['text' => $message, 'domain' => $domain, 'params' => $params];

        $this->flashBag->set($this->prefix, $messages);
    }

    /**
     * @return FlashBagInterface
     */
    private function getFlashBag()
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            return $this->flashBag;
        }

        $this->flashBag = method_exists($this->session, 'getFlashBag')
            ? $this->session->getFlashBag()
            : new FlashBag();

        return $this->flashBag;
    }
}