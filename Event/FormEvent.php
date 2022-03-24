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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormEvent extends AdminEvent
{
    /**
     * @var FormInterface<string,FormInterface>
     */
    protected FormInterface $form;

    /**
     * @return static
     */
    public static function fromOtherEvent(self $event): self
    {
        return new static($event->getElement(), $event->getRequest(), $event->getForm());
    }

    /**
     * @param Element $element
     * @param Request $request
     * @param FormInterface<string,FormInterface> $form
     */
    final public function __construct(Element $element, Request $request, FormInterface $form)
    {
        parent::__construct($element, $request);

        $this->form = $form;
    }

    /**
     * @return FormInterface<string,FormInterface>
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }
}
