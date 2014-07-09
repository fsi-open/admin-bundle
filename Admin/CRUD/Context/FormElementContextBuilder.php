<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use Symfony\Component\HttpFoundation\Request;

class FormElementContextBuilder implements ContextBuilderInterface
{
    /**
     * @var FormElementContext
     */
    private $context;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param FormElementContext $context
     */
    public function __construct(FormElementContext $context)
    {
        $this->context = $context;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($route, ElementInterface $element)
    {
        if ($route !== $this->getSupportedRoute()) {
            return false;
        }

        if ($element instanceof FormElement) {
            if (!$this->getObjectId()) {
                return true;
            }

            if (!$this->hasObject($element)) {
                throw new ContextBuilderException(sprintf("Can't find object with id %s", $this->getObjectId()));
            }

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function buildContext(ElementInterface $element)
    {
        $this->context->setElement($element, $this->getObject($element));

        return $this->context;
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_form';
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\FormElement $element
     * @return bool
     */
    protected function hasObject(FormElement $element)
    {
        $data = $element->getDataIndexer()->getData($this->getObjectId());

        return isset($data);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\FormElement $element
     * @return mixed
     */
    protected function getObject(FormElement $element)
    {
        $objectId = $this->getObjectId();
        if (!$objectId) {
            return null;
        }

        return $element->getDataIndexer()->getData($objectId);
    }

    /**
     * @return mixed
     */
    protected function getObjectId()
    {
        return $this->request->get('id', null);
    }
}
