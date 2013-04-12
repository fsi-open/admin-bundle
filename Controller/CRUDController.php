<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Structure\Doctrine\AbstractAdminElement as AbstractDoctrineAdminElement;
use FSi\Bundle\AdminBundle\Structure\AdminElementInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CRUDController extends BaseController
{
    /**
     * @param Request $request
     * @param AdminElementInterface $element
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Response
     */
    public function listAction(Request $request, AdminElementInterface $element)
    {
        if (!$element->hasDataGrid() || !$element->hasDataSource()) {
            throw $this->createNotFoundException();
        }

        $datasource = $element->getDataSource();
        $datagrid = $element->getDataGrid();
        $datasource->bindParameters($request);
        $data = $datasource->getResult();
        $datagrid->setData($data);

        if ($request->isMethod('POST'))  {
            $datagrid->bindData($request);
            $element->saveGrid();

            $datasource->bindParameters($request);
            $data = $datasource->getResult();
            $datagrid->setData($data);
        }

        $template = $this->container->getParameter('admin.templates.crud_list');
        return $this->render($template, array(
            'elements_count' => count($data),
            'element' => $element,
            'datasource_view' => $datasource->createView(),
            'datagrid_view' => $datagrid->createView()
        ));
    }

    /**
     * @param Request $request
     * @param AdminElementInterface $element
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request, AdminElementInterface $element)
    {
        if (!$element->hasCreateForm()) {
            throw $this->createNotFoundException();
        }

        $form = $element->getCreateForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                if ($element instanceof AbstractDoctrineAdminElement) {
                    /* @var $element AbstractDoctrineAdminElement */
                    $entity = $form->getData();
                    $element->save($entity);

                    return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
                }
            }
        }

        $template = $this->container->getParameter('admin.templates.crud_create');
        return $this->render($template, array(
            'element' => $element,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param AdminElementInterface $element
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, AdminElementInterface $element, $id)
    {
        if (!$element->hasEditForm()) {
            throw $this->createNotFoundException();
        }

        $indexer = $element->getDataIndexer();
        $entity = $indexer->getData($id);

        if (!isset($entity)) {
            return $this->createNotFoundException();
        }

        /* @var $form Form */
        $form = $element->getEditForm($entity);

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                if ($element instanceof AbstractDoctrineAdminElement) {
                    /* @var $element AbstractDoctrineAdminElement */
                    $entity = $form->getData();
                    $element->save($entity);
                    return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
                }
            }
        }

        $template = $this->container->getParameter('admin.templates.crud_edit');
        return $this->render($template, array(
            'element' => $element,
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    /**
     * @param AdminElementInterface $element
     * @param $id
     * @return \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction(AdminElementInterface $element, $id)
    {
        $indexer = $element->getDataIndexer();
        $entity = $indexer->getData($id);

        if (!isset($entity)) {
            return $this->createNotFoundException();
        }

        $element->delete($entity);

        return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
    }
}