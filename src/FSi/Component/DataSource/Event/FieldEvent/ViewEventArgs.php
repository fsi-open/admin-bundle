<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Event\FieldEvent;

use FSi\Component\DataSource\Field\FieldTypeInterface;
use FSi\Component\DataSource\Field\FieldViewInterface;

/**
 * Event class for Field.
 */
class ViewEventArgs extends FieldEventArgs
{
    /**
     * @var \FSi\Component\DataSource\Field\FieldViewInterface
     */
    private $view;

    /**
     * @param \FSi\Component\DataSource\Field\FieldTypeInterface $field
     * @param \FSi\Component\DataSource\Field\FieldViewInterface $view
     */
    public function __construct(FieldTypeInterface $field, FieldViewInterface $view)
    {
        parent::__construct($field);
        $this->view = $view;
    }

    /**
     * @return \FSi\Component\DataSource\Field\FieldViewInterface
     */
    public function getView()
    {
        return $this->view;
    }
}
