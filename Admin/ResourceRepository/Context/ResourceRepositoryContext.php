<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextAbstract;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Element as ResourceRepositoryElement;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceRepositoryContext extends ContextAbstract
{
    /**
     * @var ResourceRepositoryElement
     */
    protected $element;

    /**
     * @var ResourceFormBuilder
     */
    private $resourceFormBuilder;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @param HandlerInterface[] $requestHandlers
     * @param string $template
     * @param ResourceFormBuilder $resourceFormBuilder
     */
    public function __construct(
        array $requestHandlers,
        string $template,
        ResourceFormBuilder $resourceFormBuilder
    ) {
        parent::__construct($requestHandlers, $template);

        $this->resourceFormBuilder = $resourceFormBuilder;
    }

    public function setElement(Element $element): void
    {
        $this->element = $element;
        $this->form = $this->resourceFormBuilder->build($this->element);
    }

    public function hasTemplateName(): bool
    {
        return $this->element->hasOption('template') || parent::hasTemplateName();
    }

    public function getTemplateName(): string
    {
        return $this->element->hasOption('template')
            ? $this->element->getOption('template')
            : parent::getTemplateName()
        ;
    }

    public function getData(): array
    {
        return [
            'form' => $this->form->createView(),
            'element' => $this->element
        ];
    }

    protected function createEvent(Request $request): AdminEvent
    {
        return new FormEvent($this->element, $request, $this->form);
    }

    protected function getSupportedRoute(): string
    {
        return 'fsi_admin_resource';
    }

    protected function supportsElement(Element $element): bool
    {
        return $element instanceof ResourceRepositoryElement;
    }
}
