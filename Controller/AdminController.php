<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Controller;

use LogicException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

use function is_string;
use function sprintf;

class AdminController
{
    private Environment $twig;

    private RouterInterface $router;

    private string $indexActionTemplate;

    public function __construct(Environment $twig, RouterInterface $router, string $indexActionTemplate)
    {
        $this->twig = $twig;
        $this->router = $router;
        $this->indexActionTemplate = $indexActionTemplate;
    }

    public function indexAction(): Response
    {
        return new Response($this->twig->render($this->indexActionTemplate));
    }

    public function localeAction(string $_locale, Request $request): RedirectResponse
    {
        $request->getSession()->set('admin_locale', $_locale);

        if (true === $request->query->has('redirect_uri')) {
            $redirectUri = $request->query->get('redirect_uri');
            if (false === is_string($redirectUri)) {
                throw new LogicException(
                    sprintf('Query parameter redirect_uri must be a string, "%s" given.', gettype($redirectUri))
                );
            }

            return new RedirectResponse($redirectUri);
        }

        return new RedirectResponse($this->router->generate('fsi_admin'));
    }
}
