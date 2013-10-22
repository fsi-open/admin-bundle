<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Context;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface ContextInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(Request $request);

    /**
     * @return boolean
     */
    public function hasTemplateName();

    /**
     * @return string
     */
    public function getTemplateName();

    /**
     * @return array
     */
    public function getData();
}
