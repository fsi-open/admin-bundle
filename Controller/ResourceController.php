<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ResourceController extends Controller
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resourceAction(AbstractResource $element, Request $request)
    {
        $context= $this->get('admin.context.manager')->createContext('fsi_admin_resource', $element);

        if (!isset($context)) {
            throw new NotFoundHttpException(sprintf('Cant find context builder that supports %s', $element->getName()));
        }

        if (($response = $context->handleRequest($request)) !== null) {
            return $response;
        }

        return $this->render(
            $context->hasTemplateName() ? $context->getTemplateName() : $this->container->getParameter('admin.templates.resource'),
            $context->getData()
        );
    }
}
