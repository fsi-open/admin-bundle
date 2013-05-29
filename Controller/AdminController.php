<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author: Norbert Orzechowicz <norbert@fsi.pl>
 */
class AdminController extends BaseController
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $template = $this->container->getParameter('admin.templates.index_page');

        return $this->render($template);
    }

    /**
     * @return Response
     */
    public function navigationtopAction()
    {
        $template = $this->container->getParameter('admin.templates.admin_navigationtop');

        return $this->render($template, array(
            'manager' => $this->getGroupManager()
        ));
    }

    /**
     * @return Response
     */
    public function navigationleftAction()
    {
        $template = $this->container->getParameter('admin.templates.admin_navigationleft');

        return $this->render($template, array(
            'manager' => $this->getGroupManager()
        ));
    }
}