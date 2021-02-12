<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\Element;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class AdminEvent extends Event
{
    /**
     * @var Element
     */
    protected $element;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response|null
     */
    protected $response;

    public function __construct(Element $element, Request $request)
    {
        $this->element = $element;
        $this->request = $request;
    }

    public function getElement(): Element
    {
        return $this->element;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function hasResponse(): bool
    {
        return null !== $this->response;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
