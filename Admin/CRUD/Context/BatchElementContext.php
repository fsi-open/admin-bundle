<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextAbstract;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class BatchElementContext extends ContextAbstract
{
    /**
     * @var BatchElement
     */
    protected $element;

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var array
     */
    protected $indexes;

    /**
     * @param iterable<HandlerInterface> $requestHandlers
     * @param FormBuilderInterface $formBuilder
     */
    public function __construct(iterable $requestHandlers, FormBuilderInterface $formBuilder)
    {
        parent::__construct($requestHandlers);
        $this->form = $formBuilder->getForm();
    }

    public function getData(): array
    {
        return [
            'element' => $this->element,
            'indexes' => $this->indexes,
            'form' => $this->form->createView()
        ];
    }

    public function setElement(Element $element): void
    {
        if (false === $element instanceof BatchElement) {
            throw InvalidArgumentException::create(self::class, BatchElement::class, get_class($element));
        }
        $this->element = $element;
    }

    protected function createEvent(Request $request): AdminEvent
    {
        $this->indexes = $request->request->get('indexes', []);

        return new FormEvent($this->element, $request, $this->form);
    }

    protected function getSupportedRoute(): string
    {
        return 'fsi_admin_batch';
    }

    protected function supportsElement(Element $element): bool
    {
        return $element instanceof BatchElement;
    }
}
