<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context\Crud;

use FSi\Bundle\AdminBundle\Context\AbstractContextBuilder;
use FSi\Bundle\AdminBundle\Exception\MissingFormException;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractCrudCreateContextBuilder extends AbstractContextBuilder
{
    /**
     * @return CrudCreateContext
     */
    public function buildContext()
    {
        $template = $this->getElement()->hasOption('template_crud_create')
            ? $this->getElement()->getOption('template_crud_create')
            : null;

        $context = new CrudCreateContext($template);

        //Pre Set Form
        $context->setForm($this->createForm());
        //Post Set Form

        return $context;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     * @throws \FSi\Bundle\AdminBundle\Exception\MissingFormException
     */
    protected function createForm()
    {
        if (!$this->getElement()->hasCreateForm()) {
            throw new MissingFormException(sprintf('Admin object with id: "%s" doesnt have Form.', $this->getElement()->getId()));
        }

        return $this->getElement()->getCreateForm();
    }
}