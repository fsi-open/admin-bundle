<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraint;

interface ResourceInterface
{
    /**
     * Return resource name.
     *
     * @return string
     */
    public function getName();

    /**
     * Return property that is used in Resource entity to store resource value.
     *
     * @return string
     */
    public function getResourceProperty();

    /**
     * @param Constraint $constraint
     * @return mixed
     */
    public function addConstraint(Constraint $constraint);

    /**
     * @param array $options
     * @return \FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface
     */
    public function setFormOptions(array $options);

    /**
     * @param FormFactoryInterface $factory
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormBuilder
     */
    public function getFormBuilder(FormFactoryInterface $factory);
}
