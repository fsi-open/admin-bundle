<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context\Crud;

use FSi\Bundle\AdminBundle\Context\AbstractContextBuilder;
use FSi\Bundle\AdminBundle\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Exception\InvalidEntityIdException;
use FSi\Bundle\AdminBundle\Exception\MissingFormException;
use FSi\Bundle\AdminBundle\Structure\ElementInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractCrudEditContextBuilder extends AbstractContextBuilder
{
    /**
     * @var String
     */
    protected $id;

    /**
     * @param ElementInterface $element
     * @param String $id
     */
    public function __construct(ElementInterface $element, $id)
    {
        parent::__construct($element);
        $this->id = $id;
    }

    /**
     * @return CrudEditContext
     */
    public function buildContext()
    {
        $template = $this->getElement()->hasOption('template_crud_edit')
            ? $this->getElement()->getOption('template_crud_edit')
            : null;

        $context = new CrudEditContext($template);

        //Pre Set Entity Id
        $context->setEntityId($this->id);
        //Post Set Entity Id

        //Pre Set Entity
        $context->setEntity($this->getEntity());
        //Post Set Entity

        //Pre Set Form
        $context->setForm($this->createForm());
        //Post Set Form

        return $context;
    }

    /**
     * @return \Symfony\Component\Form\Test\FormInterface
     * @throws \FSi\Bundle\AdminBundle\Exception\MissingFormException
     */
    protected function createForm()
    {
        if (!$this->getElement()->hasEditForm()) {
            throw new MissingFormException(sprintf('Admin object with id: "%s" doesnt have Form.', $this->getElement()->getId()));
        }

        return $this->getElement()->getEditForm($this->getEntity());
    }

    /**
     * @return mixed
     * @throws InvalidEntityIdException
     */
    protected function getEntity()
    {
        $entity = $this->getElement()->getDataIndexer()->getData($this->id);

        if (!isset($entity)) {
            throw new InvalidEntityIdException('"%s" is not valid entity id.');
        }

        return $entity;
    }
}