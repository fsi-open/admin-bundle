<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\Element;

class CRUDListElementContext extends ListElementContext
{
    /**
     * @var string
     */
    private $listTemplate;

    /**
     * @param HandlerInterface[]|array $requestHandlers
     * @param string $listTemplate
     */
    public function __construct(array $requestHandlers, $listTemplate)
    {
        parent::__construct($requestHandlers);

        $this->listTemplate = $listTemplate;
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsElement(Element $element)
    {
        return $element instanceof AbstractCRUD;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->hasOption('template_list') ?
            $this->element->getOption('template_list') : $this->listTemplate;
    }
}
