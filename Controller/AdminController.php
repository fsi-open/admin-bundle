<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class AdminController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $indexActionTemplate;

    public function __construct(EngineInterface $templating, RouterInterface $router, string $indexActionTemplate)
    {
        $this->templating = $templating;
        $this->router = $router;
        $this->indexActionTemplate = $indexActionTemplate;
    }

    public function indexAction(): Response
    {
        return $this->templating->renderResponse($this->indexActionTemplate);
    }

    public function localeAction(string $_locale, Request $request): RedirectResponse
    {
        $request->getSession()->set('admin_locale', $_locale);

        return new RedirectResponse(
            true === $request->query->has('redirect_uri')
                ? $request->query->get('redirect_uri')
                : $this->router->generate('fsi_admin')
        );
    }
}
