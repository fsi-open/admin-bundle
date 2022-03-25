<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\Element;
use Symfony\Component\HttpFoundation\Request;

final class MovedUpTreeEvent extends AdminEvent
{
    private object $entity;

    public function __construct(Element $element, Request $request, object $entity)
    {
        parent::__construct($element, $request);
        $this->entity = $entity;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
