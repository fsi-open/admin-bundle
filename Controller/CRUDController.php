<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Form\Type\DeleteType;
use FSi\Bundle\AdminBundle\Structure\Doctrine\AbstractAdminElement as AbstractDoctrineAdminElement;
use FSi\Bundle\AdminBundle\Structure\AdminElementInterface;
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

        $template = $element->hasOption('template_crud_list')
            ? $element->getOption('template_crud_list')
            : $this->container->getParameter('admin.templates.crud_list');

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

        $template = $element->hasOption('template_crud_create')
            ? $element->getOption('template_crud_create')
            : $this->container->getParameter('admin.templates.crud_create');

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

        $template = $element->hasOption('template_crud_edit')
            ? $element->getOption('template_crud_edit')
            : $this->container->getParameter('admin.templates.crud_edit');

        return $this->render($template, array(
            'element' => $element,
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    /**
     * @param Request $request
     * @param AdminElementInterface $element
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteAction(Request $request, AdminElementInterface $element)
    {
        if (!$element->getOption('allow_delete')) {
            throw $this->createNotFoundException();
        }

        $indexes = $request->request->get('indexes', array());
        $indexer = $element->getDataIndexer();
        $form = $this->createForm(new DeleteType());
        $entities = array();

        foreach ($indexes as $index) {
            $entity = $indexer->getData($index);
            if (!isset($entity)) {
                return $this->createNotFoundException();
            }

            $entities[] = $entity;
        }

        if ($request->request->has('confirm')) {
            $form->bind($request);
            if ($form->isValid()) {
                foreach ($entities as $entity) {
                    $element->delete($entity);
                }
            }

            return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
        }

        if ($request->request->has('cancel')) {
            return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
        }

        $template = $element->hasOption('template_crud_delete')
            ? $element->getOption('template_crud_delete')
            : $this->container->getParameter('admin.templates.crud_delete');

        return $this->render($template, array(
            'indexes' => $indexes,
            'entities' => $entities,
            'element' => $element,
            'form' => $form->createView(),
        ));
    }
}