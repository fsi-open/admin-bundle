<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManagerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CRUDController
{
    /**
     * @param EngineInterface $templating
     * @param ContextManagerInterface $contextManager
     * @param string $listActionTemplate
     * @param string $createActionTemplate
     * @param string $editActionTemplate
     * @param string $deleteActionTemplate
     */
    function __construct(
        EngineInterface $templating,
        ContextManagerInterface $contextManager,
        $listActionTemplate,
        $createActionTemplate,
        $editActionTemplate,
        $deleteActionTemplate
    ) {
        $this->templating = $templating;
        $this->contextManager = $contextManager;
        $this->listActionTemplate = $listActionTemplate;
        $this->editActionTemplate = $editActionTemplate;
        $this->createActionTemplate = $createActionTemplate;
        $this->deleteActionTemplate = $deleteActionTemplate;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(AbstractCRUD $element, Request $request)
    {
        return $this->action($element, $request, 'fsi_admin_crud_list', $this->listActionTemplate);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(AbstractCRUD $element, Request $request)
    {
        return $this->action($element, $request, 'fsi_admin_crud_create', $this->createActionTemplate);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(AbstractCRUD $element, Request $request)
    {
        return $this->action($element, $request, 'fsi_admin_crud_edit', $this->editActionTemplate);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(AbstractCRUD $element, Request $request)
    {
        echo phpversion();
        die();
        return $this->action($element, $request, 'fsi_admin_crud_delete', $this->deleteActionTemplate);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param Request $request
     * @param string $route
     * @param string $defaultTemplate
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function action(AbstractCRUD $element, Request $request, $route, $defaultTemplate)
    {
        $context = $this->contextManager->createContext($route, $element);

        if (!isset($context)) {
            throw new NotFoundHttpException(sprintf('Cant find context builder that supports %s', $element->getName()));
        }

        if (($response = $context->handleRequest($request)) !== null) {
            return $response;
        }

        return $this->templating->renderResponse(
            $context->hasTemplateName() ? $context->getTemplateName() : $defaultTemplate,
            $context->getData()
        );
    }
}
