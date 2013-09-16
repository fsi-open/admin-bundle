<?php

/**
 * (c) FSi sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class FormEvent extends AdminEvent
{
    /**
     * @var \Symfony\Component\Form\Form
     */
    protected $form;

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\Form $form
     */
    public function __construct(ElementInterface $element, Request $request, Form $form)
    {
        parent::__construct($element, $request);
        $this->form = $form;
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        return $this->form;
    }
}
