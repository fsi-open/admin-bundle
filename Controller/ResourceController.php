<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManagerInterface;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ResourceController
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var \FSi\Bundle\AdminBundle\Admin\Context\ContextManagerInterface
     */
    protected $contextManager;

    function __construct(
        EngineInterface $templating,
        ContextManagerInterface $contextManager,
        $resourceActionTemplate
    ) {
        $this->templating = $templating;
        $this->contextManager = $contextManager;
        $this->resourceActionTemplate = $resourceActionTemplate;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resourceAction(AbstractResource $element, Request $request)
    {
        $context= $this->contextManager->createContext('fsi_admin_resource', $element);

        if (!isset($context)) {
            throw new NotFoundHttpException(sprintf('Cant find context builder that supports %s', $element->getName()));
        }

        if (($response = $context->handleRequest($request)) !== null) {
            return $response;
        }

        return $this->templating->renderResponse(
            $context->hasTemplateName() ? $context->getTemplateName() : $this->resourceActionTemplate,
            $context->getData()
        );
    }
}
