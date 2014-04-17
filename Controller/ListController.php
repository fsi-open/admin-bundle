<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ListController
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var \FSi\Bundle\AdminBundle\Admin\Context\ContextManager
     */
    protected $contextManager;

    /**
     * @var string
     */
    protected $listActionTemplate;

    /**
     * @param EngineInterface $templating
     * @param ContextManager $contextManager
     * @param string $listActionTemplate
     */
    function __construct(
        EngineInterface $templating,
        ContextManager $contextManager,
        $listActionTemplate
    ) {
        $this->templating = $templating;
        $this->contextManager = $contextManager;
        $this->listActionTemplate = $listActionTemplate;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractList $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(AbstractList $element, Request $request)
    {
        return $this->action($element, $request, 'fsi_admin_list', $this->listActionTemplate);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractList $element
     * @param Request $request
     * @param string $route
     * @param string $defaultTemplate
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function action(AbstractList $element, Request $request, $route, $defaultTemplate)
    {
        $context = $this->contextManager->createContext($route, $element);

        if (!isset($context)) {
            throw new NotFoundHttpException(sprintf('Can\'t find context builder that supports %s', $element->getName()));
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
