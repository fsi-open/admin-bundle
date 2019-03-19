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
use FSi\Bundle\AdminBundle\Admin\CRUD\FormHavingTemplateDataElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormElementContext extends ContextAbstract
{
    /**
     * @var FormElement
     */
    protected $element;

    /**
     * @var FormInterface
     */
    protected $form;

    public function hasTemplateName(): bool
    {
        return $this->element->hasOption('template_form') || parent::hasTemplateName();
    }

    public function getTemplateName(): ?string
    {
        return $this->element->hasOption('template_form')
            ? $this->element->getOption('template_form')
            : parent::getTemplateName()
        ;
    }

    public function getData(): array
    {
        $templateData = true === $this->element instanceof FormHavingTemplateDataElement
            ? $this->element->getTemplateData()
            : []
        ;
        return array_merge(
            $templateData,
            ['form' => $this->form->createView(), 'element' => $this->element]
        );
    }

    public function setElement(Element $element): void
    {
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
     * @return object|null
     */
    private function getObject(Request $request)
    {
        $id = $request->get('id');
        if (empty($id)) {
            $this->checkAllowAddOption();
            return null;
        }

        $object = $this->element->getDataIndexer()->getData($id);
        if (!$object) {
            throw new NotFoundHttpException(sprintf('Can\'t find object with id %s', $id));
        }

        return $object;
    }

    private function checkAllowAddOption(): void
    {
        if ($this->element->hasOption('allow_add')
            && !$this->element->getOption('allow_add')
        ) {
            throw new NotFoundHttpException(sprintf(
                'Cannot add objects through element "%s", because it has option "allow_add" set to false',
                get_class($this->element)
            ));
        }
    }
}
