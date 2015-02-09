<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ControllerAbstract
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var ContextManager
     */
    protected $contextManager;

    /**
     * @var string
     */
    protected $template;

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextManager $contextManager
     * @param string|null $resourceActionTemplate
     */
    function __construct(
        EngineInterface $templating,
        ContextManager $contextManager,
        $resourceActionTemplate = null
    ) {
        $this->templating = $templating;
        $this->contextManager = $contextManager;
        $this->template = $resourceActionTemplate;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Element $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $route
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    protected function handleRequest(Element $element, Request $request, $route)
    {
        $context = $this->contextManager->createContext($route, $element);

        if (!($context instanceof ContextInterface)) {
            throw new NotFoundHttpException(sprintf(
                'Cant find context builder that supports element with id "%s"',
                $element->getId()
            ));
        }

        if (($response = $context->handleRequest($request)) !== null) {
            return $response;
        }

        if (!isset($this->template)) {
            throw new ContextException(sprintf(
                "Context %s did not returned any response and controller %s has no template",
                get_class($context),
                __CLASS__
            ));
        }

        return $this->templating->renderResponse(
            $context->hasTemplateName() ? $context->getTemplateName() : $this->template,
            $context->getData()
        );
    }
}
