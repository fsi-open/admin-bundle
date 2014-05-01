<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Edit;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ContextBuilder implements ContextBuilderInterface
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
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

        if ($element instanceof CRUDElement) {
            if (!$element->getOption('allow_edit')) {
                throw new ContextBuilderException(sprintf("Element with id \"%s\" does not allow to edit objects", $element->getId()));
            }

            if (!$this->hasObject($element)) {
                throw new ContextBuilderException(sprintf("Cant find object with id %s", $this->getObjectId()));
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
        $this->context->setElement($element);
        $this->context->setEntity($this->getObject($element));

        return $this->context;
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_crud_edit';
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @return bool
     */
    protected function hasObject(ElementInterface $element)
    {
        $data = $element->getDataIndexer()->getData($this->getObjectId());

        return isset($data);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @return mixed
     */
    protected function getObject(ElementInterface $element)
    {
        $data = $element->getDataIndexer()->getData($this->getObjectId());

        return $data;
    }

    /**
     * @return mixed
     */
    protected function getObjectId()
    {
        return $this->request->get('id', null);
    }
}
