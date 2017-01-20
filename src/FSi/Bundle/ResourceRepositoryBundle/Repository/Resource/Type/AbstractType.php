<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraint;

abstract class AbstractType implements ResourceInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Constraint[]
     */
    protected $constraints;

    /**
     * @var array
     */
    protected $formOptions;

    /**
     * @var null|\Symfony\Component\Form\FormBuilderInterface
     */
    protected $formBuilder;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->constraints = array();
        $this->formOptions = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder(FormFactoryInterface $factory)
    {
        if (!isset($this->formBuilder)) {
            $this->formBuilder = $factory->createNamedBuilder(
                $this->getResourceProperty(),
                $this->getFormType(),
                null,
                $this->buildFormOptions()
            );
        }

        return $this->formBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function addConstraint(Constraint $constraint)
    {
        $this->constraints[] = $constraint;
    }

    /**
     * @param array $options
     * @return \FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface
     */
    public function setFormOptions(array $options)
    {
        $this->formOptions = $options;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getResourceProperty();

    /**
     * Method should return form type used to modify resource.
     *
     * @return string
     */
    abstract protected function getFormType();

    /**
     * @return array
     */
    protected function buildFormOptions()
    {
        $options = array(
            'required' => false,
            'label' => false,
        );

        $options = array_merge($options, $this->formOptions);

        if (count($this->constraints)) {
            $options = array_merge(
                $options,
                array(
                    'constraints' => $this->constraints
                )
            );
        }

        return $options;
    }
}