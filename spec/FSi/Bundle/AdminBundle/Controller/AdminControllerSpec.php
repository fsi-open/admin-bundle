<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class AdminControllerSpec extends ObjectBehavior
{
    public function let(Environment $twig, RouterInterface $router): void
    {
        $this->beConstructedWith($twig, $router, Argument::type('string'));
    }

    public function it_stores_locale_in_session(
        Request $request,
        SessionInterface $session,
        ParameterBag $requestQuery,
        RouterInterface $router
    ): void {
        $request->getSession()->willReturn($session);
        $request->query = $requestQuery;
        $requestQuery->has('redirect_uri')->willReturn(false);
        $router->generate('fsi_admin')->willReturn('admin_url');

        $session->set('admin_locale', 'qw')->shouldBeCalled();

        $response = $this->localeAction('qw', $request);
        $response->getTargetUrl()->shouldReturn('admin_url');
    }

    public function it_redirects_to_passed_redirect_uri(
        Request $request,
        SessionInterface $session,
        ParameterBag $requestQuery
    ): void {
        $request->getSession()->willReturn($session);
        $request->query = $requestQuery;
        $requestQuery->has('redirect_uri')->willReturn(true);
        $requestQuery->get('redirect_uri')->willReturn('uri_to_redirect_to');

        $response = $this->localeAction('qw', $request);
        $response->getTargetUrl()->shouldReturn('uri_to_redirect_to');
    }
}
