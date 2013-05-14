<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context;

use FSi\Bundle\AdminBundle\Structure\ElementInterface;

interface ContextBuilderInterface
{
    /**
     * @return ContextInterface
     */
    public function buildContext();

    /**
     * Check if element interface is supported by ContextBuilder.
     * Method is static so it can be used before initializing ContextBuilderObject
     *
     * @param ElementInterface $element
     * @return mixed
     */
    public static function supports(ElementInterface $element);
}
