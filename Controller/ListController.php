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
use FSi\Bundle\AdminBundle\Doctrine\Admin\ListElement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class ListController
{
    /**
     * @param EngineInterface $templating
     * @param ContextManagerInterface $contextManager
     * @param string $listActionTemplate
     */
    function __construct(
        EngineInterface $templating,
        ContextManagerInterface $contextManager,
        $listActionTemplate
    ) {
        $this->templating = $templating;
        $this->contextManager = $contextManager;
        $this->listActionTemplate = $listActionTemplate;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Doctrine\Admin\ListElement $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayAction(ListElement $element, Request $request)
    {
        $context = $this->contextManager->createContext('fsi_admin_list', $element);

        if (!isset($context)) {
            throw new NotFoundHttpException(sprintf('Cant find context builder that supports %s', $element->getName()));
        }

        if (($response = $context->handleRequest($request)) !== null) {
            return $response;
        }

        return $this->templating->renderResponse(
            $context->hasTemplateName() ? $context->getTemplateName() : $this->listActionTemplate,
            $context->getData()
        );
    }
}
