<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context\Crud;

use FSi\Bundle\AdminBundle\Context\AbstractContextBuilder;
use FSi\Bundle\AdminBundle\Form\Type\DeleteType;
use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractCrudDeleteContextBuilder extends AbstractContextBuilder
{
    /**
     * @var array
     */
    protected $indexes;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @param ElementInterface $element
     * @param FormFactoryInterface $formFactory
     * @param array $indexes
     */
    public function __construct(ElementInterface $element, FormFactoryInterface $formFactory, array $indexes = array())
    {
        parent::__construct($element);
        $this->formFactory = $formFactory;
        $this->indexes = $indexes;
    }

    /**
     * @return \FSi\Bundle\AdminBundle\Context\ContextInterface|CrudDeleteContext
     * @throws
     */
    public function buildContext()
    {
        $template = $this->getElement()->hasOption('template_crud_delete')
            ? $this->getElement()->getOption('template_crud_delete')
            : null;

        $context = new CrudDeleteContext($template);

        // Pre Set Indexes
        $context->setIndexes($this->indexes);
        // Post Set Indexes

        // Pre Set Entities
        $context->setEntities($this->getEntities($this->indexes));
        // Post Set Entities

        //Pre Set Form
        $context->setForm($this->formFactory->create(new DeleteType()));
        //Post Set Form

        return $context;
    }

    /**
     * @param array $indexes
     * @return array
     * @throws \FSi\Bundle\AdminBundle\Exception\InvalidArgumentException
     */
    protected function getEntities(array $indexes = array())
    {
        if (!count($indexes)) {
            throw new InvalidArgumentException(sprintf('Invalid indexes count in element "%s" in action Delete.', $this->getElement()->getId()));
        }

        $entities = array();
        $indexer = $this->getElement()->getDataIndexer();

        foreach ($indexes as $index) {
            $entity = $indexer->getData($index);
            if (!isset($entity)) {
                throw new InvalidArgumentException(sprintf('Entities count in element "%s" is different that entities count in action Delete.', $this->getElement()->getId()));
            }

            $entities[] = $entity;
        }

        return $entities;
    }
}