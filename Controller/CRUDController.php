<?php

/**
 * (c) FSi sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CRUDController extends Controller
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(AbstractCRUD $element)
    {
        return $this->action($element, 'fsi_admin_crud_list', 'admin.templates.crud_list');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(AbstractCRUD $element)
    {
        return $this->action($element, 'fsi_admin_crud_create', 'admin.templates.crud_create');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(AbstractCRUD $element)
    {
        return $this->action($element, 'fsi_admin_crud_edit', 'admin.templates.crud_edit');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(AbstractCRUD $element)
    {
       return $this->action($element, 'fsi_admin_crud_delete', 'admin.templates.crud_delete');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param string $route
     * @param string $defaultTemplate
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function action(AbstractCRUD $element, $route, $defaultTemplate)
    {
        $context = $this->get('admin.context.manager')->createContext($route, $element);

        if (!isset($context)) {
            throw new NotFoundHttpException(sprintf('Cant find context builder that supports %s', $element->getName()));
        }

        if (($response = $context->handleRequest($this->getRequest())) !== null) {
            return $response;
        }

        return $this->render(
            $context->hasTemplateName() ? $context->getTemplateName() : $this->container->getParameter($defaultTemplate),
            $context->getData()
        );
    }
}
