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
use FSi\Bundle\AdminBundle\Model\PositionableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class PositionableEvent extends Event
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Element
     */
    private $element;

    /**
     * @var PositionableInterface
     */
    private $object;

    public function __construct(Request $request, Element $element, PositionableInterface $object)
    {
        $this->request = $request;
        $this->element = $element;
        $this->object = $object;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getElement(): Element
    {
        return $this->element;
    }

    public function getObject(): PositionableInterface
    {
        return $this->object;
    }
}
