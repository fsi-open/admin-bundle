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
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormElementContext extends ContextAbstract
{
    protected FormElement $element;

    protected FormInterface $form;

    public function hasTemplateName(): bool
    {
        return true === $this->element->hasOption('template_form') || true === parent::hasTemplateName();
    }

    public function getTemplateName(): ?string
    {
        return true === $this->element->hasOption('template_form')
            ? $this->element->getOption('template_form')
            : parent::getTemplateName()
        ;
    }

    public function getData(): array
    {
        return ['form' => $this->form->createView(), 'element' => $this->element];
    }

    public function setElement(Element $element): void
    {
        if (false === $element instanceof FormElement) {
            throw InvalidArgumentException::create(self::class, FormElement::class, get_class($element));
        }
        $this->element = $element;
    }

    protected function createEvent(Request $request): AdminEvent
    {
        $this->form = $this->element->createForm($this->getObject($request));

        return new FormEvent($this->element, $request, $this->form);
    }

    protected function getSupportedRoute(): string
    {
        return 'fsi_admin_form';
    }

    protected function supportsElement(Element $element): bool
    {
        return $element instanceof FormElement;
    }

    /**
     * @param Request $request
     * @return array<string,mixed>|object|null
     */
    private function getObject(Request $request)
    {
        $id = $request->get('id');
        if (null === $id || '' === $id) {
            $this->checkAllowAddOption();
            return null;
        }

        return $this->element->getDataIndexer()->getData($id);
    }

    private function checkAllowAddOption(): void
    {
        if (
            true === $this->element->hasOption('allow_add')
            && true !== $this->element->getOption('allow_add')
        ) {
            throw new NotFoundHttpException(sprintf(
                'Cannot add objects through element "%s", because it has option "allow_add" set to false',
                get_class($this->element)
            ));
        }
    }
}
