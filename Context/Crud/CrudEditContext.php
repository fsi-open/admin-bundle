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
class CrudEditContext extends AbstractContext
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityId;

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
     * @param $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setEntityId($id)
    {
        $this->entityId = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }
}