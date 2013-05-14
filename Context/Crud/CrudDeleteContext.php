<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context\Crud;

use FSi\Bundle\AdminBundle\Context\AbstractContext;
use Symfony\Component\Form\FormInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CrudDeleteContext extends AbstractContext
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var array
     */
    protected $indexes;

    /**
     * @var array
     */
    protected $entities;

    /**
     * @param FormInterface $form
     * @return $this
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return FormInterface|null
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param array $indexes
     * @return $this
     */
    public function setIndexes(array $indexes)
    {
        $this->indexes = $indexes;
        return $this;
    }

    /**
     * @return array
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @param array $entities
     * @return $this
     */
    public function setEntities(array $entities)
    {
        $this->entities = $entities;
        return $this;
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }
}