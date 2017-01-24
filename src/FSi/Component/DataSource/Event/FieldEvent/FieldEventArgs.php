<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Event\FieldEvent;

use Symfony\Component\EventDispatcher\Event;
use FSi\Component\DataSource\Field\FieldTypeInterface;

/**
 * Event class for Field.
 */
class FieldEventArgs extends Event
{
    /**
     * @var \FSi\Component\DataSource\Field\FieldTypeInterface
     */
    private $field;

    /**
     * @param \FSi\Component\DataSource\Field\FieldTypeInterface $field
     */
    public function __construct(FieldTypeInterface $field)
    {
        $this->field = $field;
    }

    /**
     * @return \FSi\Component\DataSource\Field\FieldTypeInterface
     */
    public function getField()
    {
        return $this->field;
    }
}
