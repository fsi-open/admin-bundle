<?php

/**
 * (c) FSi sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface ContextBuilderInterface
{
    /**
     * @param string $route
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @return boolean
     * @throws \FSi\Bundle\AdminBundle\Exception\ContextBuilderException
     */
    public function supports($route, ElementInterface $element);

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @return \FSi\Bundle\AdminBundle\Admin\Context\ContextInterface
     */
    public function buildContext(ElementInterface $element);
}
