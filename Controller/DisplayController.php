<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Display;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class DisplayController extends ControllerAbstract
{
    /**
     * @ParamConverter("element", class="\FSi\Bundle\AdminBundle\Admin\Display\Element")
     * @param \FSi\Bundle\AdminBundle\Admin\Display\Element $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayAction(Display\Element $element, Request $request)
    {
        return $this->handleRequest($element, $request, 'fsi_admin_display');
    }
}
