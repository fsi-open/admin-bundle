<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context;

use FSi\Bundle\AdminBundle\Structure\ElementInterface;

abstract class AbstractContextBuilder implements ContextBuilderInterface
{
    /**
     * @var \FSi\Bundle\AdminBundle\Structure\ElementInterface
     */
    protected $element;

    /**
     * @param ElementInterface $element
     */
    public function __construct(ElementInterface $element)
    {
        $this->element = $element;
    }

    /**
     * @return ElementInterface
     */
    protected function getElement()
    {
        return $this->element;
    }
}