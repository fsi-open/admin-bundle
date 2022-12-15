<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Display\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextAbstract;
use FSi\Bundle\AdminBundle\Admin\Display\Element as DisplayElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\DisplayEvent;
use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

use function get_class;

class DisplayContext extends ContextAbstract
{
    /**
     * @var DisplayElement<array<string, mixed>|object>
     */
    protected DisplayElement $element;

    private Display $display;

    public function getTemplateName(): ?string
    {
        return true === $this->element->hasOption('template')
            ? $this->element->getOption('template')
            : parent::getTemplateName()
        ;
    }

    public function getData(): array
    {
        return [
            'display' => $this->display->getData(),
            'element' => $this->element,
        ];
    }

    /**
     * @param DisplayElement<array<string, mixed>|object> $element
     */
    public function setElement(Element $element): void
    {
        if (false === $element instanceof DisplayElement) {
            /** @var class-string $givenClass */
            $givenClass = get_class($element);

            throw InvalidArgumentException::create(self::class, DisplayElement::class, $givenClass);
        }

        $this->element = $element;
    }

    protected function createEvent(Request $request): AdminEvent
    {
        $object = $this->getObject($request);
        $this->display = $this->element->createDisplay($object);

        return new DisplayEvent($this->element, $request, $this->display, $object);
    }

    protected function getSupportedRoute(): string
    {
        return 'fsi_admin_display';
    }

    protected function supportsElement(Element $element): bool
    {
        return $element instanceof DisplayElement;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getObject(Request $request)
    {
        $id = $request->get('id');

        return $this->element->getDataIndexer()->getData($id);
    }
}
