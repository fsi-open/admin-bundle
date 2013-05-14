<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context;

interface ContextInterface
{
    /**
     * Return template name that will be used to render context.
     *
     * @return string
     */
    public function getTemplateName();

    /**
     * @return boolean
     */
    public function hasTemplateName();
}
