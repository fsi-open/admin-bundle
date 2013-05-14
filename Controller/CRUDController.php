<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Context\Crud\Doctrine\CrudCreateContextBuilder;
use FSi\Bundle\AdminBundle\Context\Crud\Doctrine\CrudDeleteContextBuilder;
use FSi\Bundle\AdminBundle\Context\Crud\Doctrine\CrudEditContextBuilder;
use FSi\Bundle\AdminBundle\Context\Crud\Doctrine\CrudListContextBuilder;
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
        $context = null;

        if (CrudListContextBuilder::supports($element)) {
            $contextBuilder = new CrudListContextBuilder($element);
            $context = $contextBuilder->buildContext();
        }

        if (!isset($context)) {
            throw $this->createNotFoundException(sprintf('Cant create context for element with id "%s" in List action', $element->getId()));
        }

        $context->getDataSource()->bindParameters($request);
        $data = $context->getDataSource()->getResult();
        $context->getDataGrid()->setData($data);

        if ($request->isMethod('POST'))  {
            $context->getDataGrid()->bindData($request);
            $element->saveGrid();

            $context->getDataSource()->bindParameters($request);
            $data = $context->getDataSource()->getResult();
            $context->getDataGrid()->setData($data);
        }

        return $this->render(
            $context->hasTemplateName()
                ? $context->getTemplateName()
                : $this->container->getParameter('admin.templates.crud_list'),
            array(
                'context' => $context,
                'elements_count' => count($data),
                'element' => $element,
                'datasource_view' => $context->getDataSource()->createView(),
                'datagrid_view' => $context->getDataGrid()->createView()
            )
        );
    }

    /**
     * @param Request $request
     * @param AdminElementInterface $element
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request, AdminElementInterface $element)
    {
        $context = null;

        if (CrudCreateContextBuilder::supports($element)) {
            $contextBuilder = new CrudCreateContextBuilder($element);
            $context = $contextBuilder->buildContext();
        }

        if (!isset($context)) {
            throw $this->createNotFoundException(sprintf('Cant create context for element with id "%s" in List action', $element->getId()));
        }

        if ($request->isMethod('POST')) {
            $context->getForm()->bind($request);

            if ($context->getForm()->isValid()) {
                /* @var $element AbstractDoctrineAdminElement */
                $element->save($context->getForm()->getData());

                return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
            }
        }

        return $this->render(
            $context->hasTemplateName()
                ? $context->getTemplateName()
                : $this->container->getParameter('admin.templates.crud_create'),
            array(
                'context' => $context,
                'element' => $element,
                'form' => $context->getForm()->createView()
            )
        );
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
        $context = null;

        if (CrudEditContextBuilder::supports($element)) {
            $contextBuilder = new CrudEditContextBuilder($element, $id);
            $context = $contextBuilder->buildContext();
        }

        if (!isset($context)) {
            throw $this->createNotFoundException(sprintf('Cant create context for element with id "%s" in Edit action', $element->getId()));
        }

        if ($request->isMethod('POST')) {
            $context->getForm()->bind($request);

            if ($context->getForm()->isValid()) {
                $element->save($context->getForm()->getData());
                return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
            }
        }

        return $this->render(
            $context->hasTemplateName()
                ? $context->getTemplateName()
                : $this->container->getParameter('admin.templates.crud_edit'),
            array(
                'context' => $context,
                'element' => $element,
                'form' => $context->getForm()->createView(),
                'id' => $context->getEntityId()
            )
        );
    }

    /**
     * @param Request $request
     * @param AdminElementInterface $element
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteAction(Request $request, AdminElementInterface $element)
    {
        $context = null;

        if (CrudDeleteContextBuilder::supports($element)) {
            $contextBuilder = new CrudDeleteContextBuilder(
                $element,
                $this->get('form.factory'),
                $request->request->get('indexes', array())
            );
            $context = $contextBuilder->buildContext();
        }

        if (!isset($context)) {
            throw $this->createNotFoundException(sprintf('Cant create context for element with id "%s" in Edit action', $element->getId()));
        }

        if ($request->request->has('confirm')) {
            $context->getForm()->bind($request);
            if ($context->getForm()->isValid()) {
                foreach ($context->getEntities() as $entity) {
                    $element->delete($entity);
                }
            }

            return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
        }

        if ($request->request->has('cancel')) {
            return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
        }

        return $this->render(
            $context->hasTemplateName()
                ? $context->getTemplateName()
                : $this->container->getParameter('admin.templates.crud_delete'),
            array(
                'context' => $context,
                'indexes' => $context->getIndexes(),
                'entities' => $context->getEntities(),
                'element' => $element,
                'form' => $context->getForm()->createView(),
            )
        );
    }
}