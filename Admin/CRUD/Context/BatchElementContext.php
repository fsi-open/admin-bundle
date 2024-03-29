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
use RuntimeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class BatchElementContext extends ContextAbstract
{
    /**
     * @var BatchElement<array<string, mixed>|object>|null
     */
    protected ?BatchElement $element;
    /**
     * @var FormInterface<string,FormInterface>
     */
    protected FormInterface $form;
    /**
     * @var array<int,int|string>|null
     */
    protected ?array $indexes = null;

    /**
     * @param iterable<HandlerInterface> $requestHandlers
     * @param FormBuilderInterface<string,FormBuilderInterface> $formBuilder
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

    /**
     * @param BatchElement<array<string, mixed>|object> $element
     */
    public function setElement(Element $element): void
    {
        if (false === $element instanceof BatchElement) {
            /** @var class-string $givenClass */
            $givenClass = get_class($element);

            throw InvalidArgumentException::create(self::class, BatchElement::class, $givenClass);
        }

        $this->element = $element;
    }

    protected function createEvent(Request $request): AdminEvent
    {
        if (null === $this->element) {
            throw new RuntimeException("Unable to handle request without setting element first");
        }
        $this->indexes = $request->request->all()['indexes'] ?? [];

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
